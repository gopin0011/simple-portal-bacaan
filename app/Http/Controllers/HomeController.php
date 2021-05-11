<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
// use Goutte\Client;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function cnnNasional()
    {
        $url = 'https://the-lazy-media-api.vercel.app/api/games/console-game?page=1';
        $url = 'https://masak-apa-tomorisakura.vercel.app/api/recipes';
        // $url = 'https://masak-apa-tomorisakura.vercel.app/api/recipe/cara-membuat-roti-canai';

        $headers = [
            'Content-Type' => 'application/json',
            // 'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36',
        ];

        $client = new \GuzzleHttp\Client([
            'headers' => $headers,
        ]);

        $response = $client->request('GET', $url);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        echo"<pre>";
        print_r(json_decode($content));
        echo"</pre>";
    }

    public function newsapi()
    {
        $result = [];

        $url = 'http://newsapi.org/v2/top-headlines?country=id&page=2';

        $headers = [
            'Content-Type' => 'application/json',
            'X-Api-Key' => '708b22f5a1d04a9b97d1eb348e2428fa',
        ];

        $client = new \GuzzleHttp\Client([
            'headers' => $headers,
        ]);

        $response = $client->request('GET', $url);
        $statusCode = $response->getStatusCode();

        if ($statusCode == 200) {
            $content = json_decode($response->getBody());
            $result = $content->articles;
        }

        echo"<pre>";
        dump($content);
        echo"</pre>";

        return view('blog', [
            'result' => $result,
        ]);
    }

    public function liputan6Index()
    {
        $result = [];

        $original_url = 'https://www.liputan6.com/news/indeks';
        $url = 'http://localhost:8080/liputan6';

        $headers = [
            'Content-Type' => 'application/json',
            // 'X-Api-Key' => '708b22f5a1d04a9b97d1eb348e2428fa',
        ];

        $client = new \GuzzleHttp\Client([
            'headers' => $headers,
        ]);

        try {
            $response = $client->request('GET', $url);

            $statusCode = $response->getStatusCode();
            $content = json_decode($response->getBody());

            if ($statusCode == 200 && $content->status) {
                $result['page'] = $content->page;
                $result['totalPage'] = $content->totalPage;
                $result['result'] = $content->result;
            }
        }
        catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }

        return view('blog', [
            'result' => $result,
        ]);
    }

}
