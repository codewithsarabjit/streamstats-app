<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Laravel\Cashier\Cashier;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        
        // $stripe = Cashier::stripe();
        // $products = $stripe->products->all(); 
        // $prices   = $stripe->prices->all();  
        // $products = array_reverse($products->data);
        // $prices = $prices->data;
        // $basicProduct = $products[0]['name'];
        // $basicPrice = $prices[0]['id'];

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
        
        $user->createOrGetStripeCustomer();
        // $user->newSubscription($basicProduct, $basicPrice)->add();
        return $user;
    }
}
