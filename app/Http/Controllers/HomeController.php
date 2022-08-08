<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect; 
use App\Models\User;

class HomeController extends Controller
{
    public function dashboard(Request $request) 
    {
        $stripe = Cashier::stripe();
        $products = $stripe->products->all(); 
        $prices   = $stripe->prices->all();   
        $subscription = Auth::user()->subscriptions()->active()->latest()->first();
        return Inertia::render('Dashboard', [
            'products' => array_reverse($products->data), 
            'prices' => $prices->data,
            'subscription' => $subscription,
            'user' => auth()->user(),
        ]);
    }
    public function index(Request $request) 
    {
        $stripe = Cashier::stripe();
        $products = $stripe->products->all(); 
        $prices   = $stripe->prices->all();   
        $subscription = Auth::user()->subscriptions()->active()->latest()->first();
        return Inertia::render('Plans', [
            'products' => array_reverse($products->data), 
            'prices' => $prices->data,
            'subscription' => $subscription
        ]);
    }

    public function update(Request $request) 
    {
        $requestData = $request->all();
        if ($requestData["unit_amount"] === 0) {
            $this->subscribe($requestData["product"], $requestData["id"], Auth::user(), "Basic");
            return Redirect::route('plans.index');
        }
        return Redirect::route('plans.pay', [
            'prodid' => $requestData["product"]
        ]);
    }

    public function pay($prodid) 
    {
        $error = $_GET["error"] ?? "";
        $customer = auth()->user()->createOrGetStripeCustomer();
        $customer->address = [
            'city' => 'Mohali',
            'country' => 'India',
            'line1' => 'Phase 3a',
            'postal_code' => '160059',
            'state' => 'Punjab'
        ];
        $intent = auth()->user()->createSetupIntent([
            'description' => "Saas application Subscription",
            'customer' => $customer
        ]);
        $paymentMethods = auth()->user()->paymentMethods();
                
        return Inertia::render('Pay', [
            'prodid' => $prodid,
            'formUrl' => route('products.purchase', $prodid),
            'intent' => $intent,
            'token' => csrf_token(),
            'error' => $error,
            'stripesecret' => env('STRIPE_KEY'),
            'cards' => $paymentMethods
        ]);
    }

    public function purchase(Request $request, $productId)
    {
        $user          = $request->user();
        $paymentMethod = $request->input('payment_method');

        
        try {
            $stripe = Cashier::stripe();
            $prices = $stripe->prices->all(['product' => $productId]);
            $products = $stripe->products->all();
            $newProduct = array_values(array_filter($products->data, function ($prod) use ($productId) {
                return $prod['id'] === $productId;
            }))[0];
            
            $customer = $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            $customer->address = [
                'city' => 'Mohali',
                'country' => 'India',
                'line1' => 'Phase 3a',
                'postal_code' => '160059',
                'state' => 'Punjab'
            ];
            
            $user->subscription('Basic')->cancelNow();
            $user->subscription('Starter')->cancelNow();
            $user->subscription('Enterprise')->cancelNow();
            
            $user->newSubscription($newProduct->name, $prices->data[0]->id)->add();
            
        } catch (\Exception $exception) {
            return Redirect::route('plans.pay', [
                'prodid' => $productId,
                'error' => $exception->getMessage(),
            ]);
        }        
        return Redirect::route('plans.index');
    }

    protected function subscribe($prodId, $priceId, $user, $newSubscription) {
        $subscribed = $user->subscribed($newSubscription);
        
        if ($subscribed === null || $subscribed === false) {
            $stripe = Cashier::stripe();
            $products = $stripe->products->all();
            $currentProduct = array_values(array_filter($products->data, function ($prod) use ($prodId) {
                return $prod['id'] === $prodId;
            }))[0];
            $user->newSubscription($currentProduct['name'], $priceId)->add();
        }
        return true;
    }
}
