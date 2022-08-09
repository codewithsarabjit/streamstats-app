<?php 
namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * This function is responsible to validate the authorized twitch user with our database and create account if not present, otherwise return the user
     */
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