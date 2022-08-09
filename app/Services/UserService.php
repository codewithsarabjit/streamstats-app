<?php 
namespace App\Services;

use App\Models\User;

class UserService
{
    public static function saveTwitchUser($response)
    {
        $user = User::where('twitch_id', $response->id)->first();
        if ($user){
            return $user;
        }
        return User::create([
            'name'          => $response->name,
            'email'         => $response->email,
            'username'      => $response->name,
            'twitch_id'   => $response->id
        ]);
    }
}
?>