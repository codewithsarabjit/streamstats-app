<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect; 
use App\Models\User;
use App\Services\UserService;
use App\Services\StreamService;
use Laravel\Socialite\Facades\Socialite;
use romanzipp\Twitch\Twitch;

class HomeController extends Controller
{
    public function loginWithTwitch(Request $request) 
    {
        return Socialite::driver('twitch')->redirect();
    }

    public function handleProviderCallback(Request $request) 
    {
        $user = UserService::saveTwitchUser(Socialite::driver('twitch')->user());
        Auth::login($user);
        return Redirect::route('dashboard');
    }

    public function dashboard(Request $request) 
    {
        // $topStreams = StreamService::syncStreams();
        $totalNumberOfStreamsPerGame = StreamService::totalNumberOfStreamsPerGame();
        $topGamesByViewersPerGame = StreamService::topGamesByViewersPerGame();
        $medianViewersOfAllStreams = StreamService::medianViewersOfAllStreams();
        $top100StreamsByViewersCount = StreamService::top100StreamsByViewersCount();
        $totalNumberOfStreamsByStartTime = StreamService::totalNumberOfStreamsByStartTime();
        $followedStreamsIntop1000 = StreamService::followedStreamsIntop1000();
        
        return Inertia::render('Dashboard', [
            'user' => auth()->user(),
            'totalNumberOfStreamsPerGame' => $totalNumberOfStreamsPerGame,
            'topGamesByViewersPerGame' => $topGamesByViewersPerGame,
            'medianViewersOfAllStreams' => $medianViewersOfAllStreams,
            'top100StreamsByViewersCount' => $top100StreamsByViewersCount,
            'totalNumberOfStreamsByStartTime' => $totalNumberOfStreamsByStartTime,
            'followedStreamsIntop1000' => $followedStreamsIntop1000,
        ]);
    }
}
