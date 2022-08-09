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
    /**
     * This function is responsible to redirect the user to twitch authorize page
     */
    public function loginWithTwitch(Request $request) 
    {
        return Socialite::driver('twitch')->redirect();
    }

    /**
     * This function is responsible to save the authorized and valid twitch user into the users table, login the user, and redirect to the dashboard
     */
    public function handleProviderCallback(Request $request) 
    {
        $user = UserService::saveTwitchUser(Socialite::driver('twitch')->user());
        Auth::login($user);
        return Redirect::route('dashboard');
    }

    /**
     * This function is responsible to display all the required data on the dashboard of the loggedin user as per the requirement
     */
    public function dashboard() 
    {
        $totalNumberOfStreamsPerGame = StreamService::totalNumberOfStreamsPerGame();
        $topGamesByViewersPerGame = StreamService::topGamesByViewersPerGame();
        $medianViewersOfAllStreams = StreamService::medianViewersOfAllStreams();
        $top100StreamsByViewersCount = StreamService::top100StreamsByViewersCount();
        $totalNumberOfStreamsByStartTime = StreamService::totalNumberOfStreamsByStartTime();
        $followedStreamsIntop1000 = StreamService::followedStreamsIntop1000();
        $diffViewersUserFollowedAnd1000thStream = StreamService::diffViewersUserFollowedAnd1000thStream();
        $sharedTagsUserFollowedAndTop1000Streams = StreamService::sharedTagsUserFollowedAndTop1000Streams();
        
        return Inertia::render('Dashboard', [
            'user' => auth()->user(),
            'totalNumberOfStreamsPerGame' => $totalNumberOfStreamsPerGame,
            'topGamesByViewersPerGame' => $topGamesByViewersPerGame,
            'medianViewersOfAllStreams' => $medianViewersOfAllStreams,
            'top100StreamsByViewersCount' => $top100StreamsByViewersCount,
            'totalNumberOfStreamsByStartTime' => $totalNumberOfStreamsByStartTime,
            'followedStreamsIntop1000' => $followedStreamsIntop1000,
            'diffViewersUserFollowedAnd1000thStream' => $diffViewersUserFollowedAnd1000thStream,
            'sharedTagsUserFollowedAndTop1000Streams' => $sharedTagsUserFollowedAndTop1000Streams,
        ]);
    }
}
