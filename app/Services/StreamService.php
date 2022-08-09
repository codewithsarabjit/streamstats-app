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
        
    }

    public static function followedSteamsIntop1000()
    {
        
    }

    public static function diffViewersUserFollowedAnd1000thStream()
    {
        
    }

    public static function sharedTagsUserFollowedAndTop1000Streams()
    {
        
    }

}
?>