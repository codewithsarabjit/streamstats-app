<?php 
namespace App\Services;

use App\Models\Stream;
use romanzipp\Twitch\Twitch;
use DB;

class StreamService
{
    /**
     * This function is responsible to sync the lastest top 1000 live streams data from twitch API into the streams table in our database. I first truncate the existing records in the table because this function is being hit via cron command every 15 minutes.
     */
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
                    'tag_ids'      => implode(",", $item->tag_ids),
                    'started_at' => $item->started_at
                ]);
            }
            if ($i===9) {
                break;
            }
            $i++;
        } while ($topStreams->hasMoreResults());
    }

    /**
     * This function is responsible to return total number of live streams per game
     */
    public static function totalNumberOfStreamsPerGame()
    {
        return Stream::select("game", DB::raw('count(*) as streamsCount'))->groupBy("game")->orderBy("streamsCount", "desc")->limit(10)->get();
    }

    /**
     * This function is responsible to return top games by viewers count per game
     */
    public static function topGamesByViewersPerGame()
    {
        return Stream::select("game", DB::raw('SUM(views) as viewsCount'))->groupBy("game")->orderBy("viewsCount", "desc")->limit(10)->get();
    }

    /**
     * This function is responsible to return the median viewers count out of all the live streams in our table
     */
    public static function medianViewersOfAllStreams()
    {
        return Stream::limit(1000)->get()->avg("views");
    }

    /**
     * This function is responsible to return top 100 streams by viewers count
     */
    public static function top100StreamsByViewersCount($sort = "desc")
    {
        return Stream::select("title", "views")->orderBy("views", $sort)->limit(100)->get();
    }

    /**
     * This function is responsible to return total number of streams by start time (within the last rounded hour)
     */
    public static function totalNumberOfStreamsByStartTime()
    {
        $lastHourTime = date("Y-m-d H:00:00",time());
        return Stream::where("started_at", '>', $lastHourTime)->get()->count();
    }

    /**
     * This function is responsible to return the user followed streams which are in top 1000
     */
    public static function followedStreamsIntop1000()
    {
        $twitch = new Twitch;
        $loggedInUserFollowed = $twitch->getUsersFollows(['from_id' => auth()->user()->twitch_id]);
        $followedUsers = array_column($loggedInUserFollowed->data(), "to_name");
        return Stream::select("channel_name")->whereIn("channel_name", $followedUsers)->get();
    }

    /**
     * This function is responsible to return the amount of viewers that are needed by lowest viewer count user followed stream to be in top 1000 list
     */
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

    /**
     * This function is responsible to return the shared tags between user followed channels and the top 1000 streams
     */
    public static function sharedTagsUserFollowedAndTop1000Streams()
    {
        $twitch = new Twitch;
        $loggedInUserFollowed = $twitch->getUsersFollows(['from_id' => auth()->user()->twitch_id]);        
        $followedUsersIds = array_column($loggedInUserFollowed->data(), "to_id");
        $sharedTags = [];
        foreach ($followedUsersIds as $key => $broadcaster_id) {
            $tags = $twitch->getStreamTags(["broadcaster_id" => $broadcaster_id]);
            $tagsData = $tags->data();
            foreach ($tagsData as $key => $tag) {
                $sharedCount = Stream::where('tag_ids', 'like', '%' . $tag->tag_id . '%')->count();
                if ($sharedCount > 0) {
                    $sharedTags[] = $tag;
                }
            }
        }
        return $sharedTags;
    }

}
?>