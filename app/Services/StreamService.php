<?php 
namespace App\Services;

use App\Models\Stream;
use romanzipp\Twitch\Twitch;
use DB;

class StreamService
{
    public static function syncStreams()
    {
        $twitch = new Twitch;    
        Stream::truncate();
        $i=0;
        do {
            $nextCursor = null;
        
            if (isset($topStreams)) {
                $nextCursor = $topStreams->next();
            }
            $topStreams = $twitch->getStreams(['first' => 100], $nextCursor);
            foreach ($topStreams->data() as $item) {
                Stream::create([
                    'channel_name' => $item->user_name,
                    'title'      => $item->title,
                    'game'       => $item->game_name,
                    'views'      => $item->viewer_count,
                    'started_at' => $item->started_at
                ]);
            }
            if ($i===19) {
                break;
            }
            $i++;
        } while ($topStreams->hasMoreResults());
    }

    public static function totalNumberOfStreamsPerGame()
    {
        return Stream::select("game", DB::raw('count(*) as streamsCount'))->groupBy("game")->orderBy("streamsCount", "desc")->limit(10)->get();
    }
    
    public static function topGamesByViewersPerGame()
    {
        return Stream::select("game", DB::raw('SUM(views) as viewsCount'))->groupBy("game")->orderBy("viewsCount", "desc")->limit(10)->get();
    }

    public static function medianViewersOfAllStreams()
    {
        return Stream::limit(1000)->get()->avg("views");
    }

    public static function top100StreamsByViewersCount($sort = "desc")
    {
        return Stream::select("title", "views")->orderBy("views", $sort)->limit(100)->get();
    }

    public static function totalNumberOfStreamsByStartTime()
    {
        $lastHourTime = date("Y-m-d H:00:00",time());
        return Stream::where("started_at", '>', $lastHourTime)->get()->count();
    }

    public static function followedStreamsIntop1000()
    {
        $twitch = new Twitch;
        $loggedInUserFollowed = $twitch->getUsersFollows(['from_id' => auth()->user()->twitch_id]);
        $followedUsers = array_column($loggedInUserFollowed->data(), "to_name");
        return Stream::select("channel_name")->whereIn("channel_name", $followedUsers)->get();
    }

    public static function diffViewersUserFollowedAnd1000thStream()
    {
        $twitch = new Twitch;
        $loggedInUserFollowed = $twitch->getUsersFollows(['from_id' => auth()->user()->twitch_id]);        
        $followedUsersIds = array_column($loggedInUserFollowed->data(), "to_id");
        
        $thousandthStreamViews = Stream::orderBy("views", "asc")->first();
        $minViewsintop1000 = $thousandthStreamViews->views ?? 0;
        
        $followedStreams = $twitch->getStreams(['user_id' => $followedUsersIds]);
        $followedStreamsCount = count($followedStreams->data());
        $leastViewFollowed = (!empty($followedStreams->data()) && isset($followedStreams->data()[$followedStreamsCount-1])) ? $followedStreams->data()[$followedStreamsCount-1]->viewer_count : 0;
        
        return $leastViewFollowed < $minViewsintop1000 ? ($minViewsintop1000 - $leastViewFollowed)+1 : 0;
    }

    public static function sharedTagsUserFollowedAndTop1000Streams()
    {
        
    }

}
?>