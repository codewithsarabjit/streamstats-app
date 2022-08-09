<?php 
namespace App\Services;

use App\Models\Stream;
use romanzipp\Twitch\Twitch;

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
            if ($i===9) {
                break;
            }
            $i++;
        } while ($topStreams->hasMoreResults());

    }
}
?>