<?php

namespace App\Http\Controllers;

use Aerni\Spotify\Spotify;

use App\Artists;
use App\Genre;
use App\Service\SpotifyService;
use App\Tracks;
use Auth;
use Cache;
use Illuminate\Http\Request;
use Session;
use SpotifyWebAPI;
use View;
use DB;

class SpotifyController extends Controller
{
    public function searchTrack(SpotifyService $service)
    {
        $count = 0;
        while ($count < 30) {
            $random = $service->readable_random_string();
            $test = Spotify::searchItems($random . "*", 'track')->get();
            $items = $test["tracks"]["items"];

            foreach ($items as $item) {
                $preview = $item["preview_url"];
                if ($preview) {
                    $artistName = $item["artists"][0]["name"];
                    $artistID = $item["artists"][0]["id"];
                    $artistURL = $item["artists"][0]["external_urls"]["spotify"];
                    $this->addArtist($artistID, $artistName, $artistURL);
                    $this->getGenreByArtist($artistID);
                    $this->addTrack($item["id"], $artistID, $item["name"], $item["album"]["images"][0]["url"], $preview);
                }
            }
            $count++;
            sleep(3);
        }
    }

    private function getGenreByArtist($id)
    {
        $artist = Spotify::artist($id)->get();
        if ($artist) {
            $genres = $artist["genres"];
            foreach ($genres as $genre) {
                if (searchGenre($genre)) {
                    addGenre($genre);
                }
                addGenreArtist(genreId($genre), $id);
            }
        }
    }
}
