<?php

namespace App\Console\Commands;
use Spotify;
use Illuminate\Console\Command;

class Search extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $search = \App\Models\Search::where('status', 0)->get();

        foreach($search as $item){
            $flag = false;
            $test = Spotify::searchArtists($item->artist)->get();
            foreach($test["artists"]["items"] as $artist){
                $artistName = $artist["name"];
                $artistURL = $artist["external_urls"]["spotify"];
                $artistId = $artist["id"];
                $genres = $artist["genres"];

                if($artistId) {
                    $albums = Spotify::artistAlbums($artistId)->get();

                    if(count($albums["items"]) > 0){

                        $album = $albums["items"][0];
                        $tracks = Spotify::albumTracks($album["id"])->get();


                        if(count($tracks["items"]) > 0){

                            $track = $tracks["items"][0];

                            addArtist($artistId, $artistName, $artistURL);

                            addGenreList($genres, $artistId);

                            $preview = $track["preview_url"];

                            addTrack($track["id"], $artistId, $track["name"], $album["images"][0]["url"], $preview);
                            $flag = true;
                        }

                    }

                }

                if($flag){
                    $item->status = 1;
                    $item->save();
                }
            }
        }


    }
}
