<?php

namespace App\Http\Controllers;

use App\Models\Artists;
use App\Models\Genre;
use App\Models\Search;
use App\Models\Tracks;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use SpotifySeed;
use Spotify;

class HomeController extends Controller
{
    public function index()
    {
        $genres = Genre::where("slug", "!=", "")->get();
        return view('home', compact('genres'));
    }

    public function cmdSearchRun(){
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

    public function cmdSearchRelated(){
        $artists = DB::table("artists")->inRandomOrder()->limit(200)->get();

        foreach ($artists as $item) {
            $test = Spotify::artistRelatedArtists($item->artist_id)->get();

            foreach ($test["artists"] as $artist) {
                $artistName = $artist["name"];
                $artistURL = $artist["external_urls"]["spotify"];
                $artistId = $artist["id"];
                $genres = $artist["genres"];

                if ($artistId) {
                    $albums = Spotify::artistAlbums($artistId)->get();

                    if (count($albums["items"]) > 0) {

                        $album = $albums["items"][0];
                        $tracks = Spotify::albumTracks($album["id"])->get();


                        if (count($tracks["items"]) > 0) {

                            $track = $tracks["items"][0];

                            addArtist($artistId, $artistName, $artistURL);

                            addGenreList($genres, $artistId);

                            $preview = $track["preview_url"];

                            addTrack($track["id"], $artistId, $track["name"], $album["images"][0]["url"], $preview);
                        }
                    }
                }
            }
            sleep(0.3);
        }
    }

    public function test()
    {
        $data = file_get_contents("http://everynoise.com/cities.html");

        $pattern = '@<tr valign=top>(.*?)</tr>@si';

        preg_match_all($pattern, $data, $yazilar);

        preg_match_all('@<a (.*?)>(.*?)</a>@si', $yazilar[0][1], $test);
        //@<td class=city>(.*?)</td>@si 1
        // @<a (.*?)>(.*?)</a>@si 2
//        dd($yazilar);

        foreach ($yazilar as $item) {
            $count = 0;
            foreach ($item as $one) {

                if ($count != 0) {
                    preg_match('@<td class=city>(.*?)</td>@si', $one, $city);

                    $city = $city[1];

                    preg_match_all('@<a (.*?)>(.*?)</a>@si', $one, $genre);

                    foreach ($genre[2] as $item) {
                        $id = DB::table('city_name')->where('name', $city)->first();

                        if (!searchGenre($item)) {
                            addGenre($item);
                        }

                        addCities($id->id, genreId($item));
                    }


                }
                $count++;
            }


        }

//        return view('test');
    }

    public function genre($id)
    {
        $id = Genre::where('slug', $id)->first();
        $artist = DB::table('artist_genre')->where('genre_id', $id->id)->get();
        $artists = array();
        foreach ($artist as $item) {
            $artistItem = Artists::where('artist_id', $item->artist_id)->first();
            if ($artistItem) {
                $artists[] = $artistItem;
            }
        }

        if (Auth::check()) {
            $auth = true;
        } else {
            $auth = false;
        }

        return view('genre', compact('artists', 'auth'));
    }

    public function track($id)
    {
        $track = Tracks::where('artist_id', $id)->first();
        if ($track) {
            return response()->json(['ok' => $track->cdn]);
        }
    }

    public function search($key)
    {
        $results = array();

        $artist = Artists::where('name', 'LIKE', '%'.$key.'%')->first();

        if ($artist) {
            $artist_genre = DB::table('artist_genre')->where('artist_id', $artist->artist_id)->get();


            foreach ($artist_genre as $item) {
                $genre = Genre::where('id', $item->genre_id)->where("slug", "!=", "")->first();
                     if ($genre) {
                         $results[] = "<a class='genre_item' href='" . route('genre', ['id' => $genre->slug]) . "'>" . $genre->name . "</a>, ";
                     }
            }

        }
        if (count($results) > 0) {
            return $results;
        }

        $check = Search::where("artist", $key)->first();

        if(!$check){
            $search = new Search();
            $search->artist = $key;
            $search->save();
        }

        return 0;
    }

    public function genres($id)
    {
        $artist = Artists::where('id', $id)->first();
        $artist_genre = DB::table('artist_genre')->where('artist_id', $artist->artist_id)->get();
        $genres = array();
        foreach ($artist_genre as $item) {
            $genres[] = Genre::where('id', $item->genre_id)->first();
        }
        return json_encode($genres);
    }

    public function updateArtist(Request $request)
    {

        $id = json_decode($request->id);
        $artist = Artists::find($id);
        $artist->name = $request->name;
        $artist->link = $request->link;

        $artist->save();

        return response()->json(['ok' => json_decode($request->name)]);

    }

    public function removeGenre($id)
    {
        $id = explode("|", $id);
        $artist = Artists::where('id', $id[0])->first();
        $artist_genre = DB::table('artist_genre')->where(['artist_id' => $artist->artist_id, 'genre_id' => $id[1]])->delete();

        return response()->json(["ok" => "ok"]);
    }

    public function addGenre($id)
    {
        $id = explode("|", $id);
        $artist = Artists::where('id', $id[0])->first();
        $genre = Genre::where('name', $id[1])->first();
        DB::table('artist_genre')->insert([
            'artist_id' => $artist->artist_id,
            'genre_id' => $genre->id
        ]);

    }

    public function genreList()
    {
        return Genre::all();
    }

    public function cities()
    {
        $list = DB::table('city_names')->get();


        return view('cities', compact('list'));
    }

    public function list()
    {
        return view('list');
    }

    public function artist($id)
    {
        $artist = Artists::where('id', $id)->first();

        return $artist;
    }
}
