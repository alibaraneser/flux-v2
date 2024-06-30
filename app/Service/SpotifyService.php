<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;

class SpotifyService
{
    public function readable_random_string()
    {
        $length = $this->random_index();

        $temp = $length;
        if($length % 2 != 0){
            $length++;
        }
        $string     = '';
        $vowels     = array("a","e","i","o","u",'ı','ö','ü');
        $consonants = array(
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm','ğ','j',
            'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
        );
        // Seed it
        srand((double) microtime() * 1000000);
        $max = $length/2;
        for ($i = 1; $i <= $max; $i++)
        {
            $string .= $consonants[rand(0,19)];
            $string .= $vowels[rand(0,4)];
        }
        if($temp % 2 != 0){
            return mb_substr($string,0,$temp);
        }
        return $string;
    }
    public function random_index(){
        $array = array(3,3,3,3,3,3,3,3,3,3,3,2,2,2,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,5,5,5,5,5,5,5,5,5,5,5,5,5,5,6,6,6,6,6,6,6,7,7,7,7,8,9);

        return $array[array_rand($array)];
    }
    public function searchArtist($id){
        $list = \App\Models\Artists::where('artist_id',$id)->first();

        if($list) {
            return true;
        }
        return false;
    }
    public function addArtist($id,$name,$link){
        $list = \App\Models\Artists::where('artist_id',$id)->first();
        if(!$list) {
            $list = \App\Models\Artists::create([
                'artist_id' => $id,
                'name' => $name,
                'link' => $link
            ]);
        }
    }
    public function addTrack($id,$artist,$name,$image,$cdn){
        if(!$image){
            $image = "";
        }
        if(!$cdn){
            $cdn = "";
        }
        $list = \App\Models\Tracks::create([
            'track' => $id,
            'artist_id' => $artist,
            'name' => $name,
            'image' => $image,
            'cdn' => $cdn
        ]);
    }
    public function searchGenre($id){
        $list = \App\Models\Genre::where('name',$id)->first();

        if($list) {
            return true;
        }
        return false;
    }
    public function addGenre($id){
        if($id){
            DB::table("genres")->insert([
                'name' => $id,
                'slug' => permalink($id),
                'count' => 0
            ]);
        }
    }
    public function addGenreArtist($genre,$artist){
        if($genre) {
            $check = DB::table('artist_genre')->where('artist_id', $artist)->where('genre_id', $genre)->first();
            if (!$check) {
                DB::table('artist_genre')->insert([
                    'artist_id' => $artist,
                    'genre_id' => $genre
                ]);
            }
        }
    }
    public function genreId($id){

        $list = \App\Models\Genre::where('name',$id)->first();

        if($list) {
            return $list->id;
        }
    }
    public function market(){
        $array = array('AD', 'JP', 'IL', 'HK', 'ID', 'MY', 'PH', 'SG', 'TW', 'TH','VN','IN','AT','BE','BG','CY','CZ',
            'DK','EE','FI','FR','DE','GR','HU','IS','IE','IT','LV','LI','LT','LU','MT','MC','NL','NO','PL','PT','RO','SK','ES','SE','CH'
        ,'TR','GB','AR','BO','BR','CL','CO','CR','DO','EC','SV','GT','HN','MX','NI','PA','PY','PE','UY','CA',
            'US','DZ','EG','MA','ZA','TN','AU','NZ');

        return $array[array_rand($array)];

    }
    public function permalink($string)
    {
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
        return $string;
    }
    public function addCities($code,$genre){
        if($code){
            \App\Models\Cities::create([
                'code' => $code,
                'genre' => $genre
            ]);
        }
    }
    public function searchCities($code,$genre){
        $list = \App\Models\Cities::where('code',$code)->where('genre',$genre)->first();

        if($list){
            return true;
        }
        return false;
    }

    public function addGenreList($genres, $artistId){
        foreach ($genres as $genre) {
            if (!$this->searchGenre($genre)) {
                $this->addGenre($genre);
            }
            $this->addGenreArtist($this->genreId($genre), $artistId);
        }
    }

    public function getGenre($id){
        $genre = \App\Models\Genre::where("id", $id)->first();
        if($genre){
            return $genre;
        }
    }
}
