<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Facades\Redis;
// use Goutte\Client;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function masakApaHariIni()
    {
        $url = 'https://masak-apa-tomorisakura.vercel.app/api/recipes';
        // $url = 'https://masak-apa-tomorisakura.vercel.app/api/recipe/{slug}';

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

    public function newsApiOrg()
    {
        $result = [];

        $url = 'http://newsapi.org/v2/top-headlines?country=id&page=2';

        $headers = [
            'Content-Type' => 'application/json',
            'X-Api-Key' => env('X_API_KEY_NEWS_API_ORG'),
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

    public function liputan6Index(Request $request)
    {
        $result = [];

        $urlApi = 'http://localhost:8080/liputan6';
        $urlApi .= ($request->page) ? '?page='.$request->page : '?page=1';

        $redisKey = $urlApi;

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $client = new \GuzzleHttp\Client([
            'headers' => $headers,
        ]);

        if (!Cache::has($redisKey))
        {
            try {
                $response = $client->request('GET', $urlApi);

                $statusCode = $response->getStatusCode();
                $content = json_decode($response->getBody());

                if ($statusCode == 200 && $content->status) {
                    $result = Cache::remember($redisKey, (10 * 60), function () use ($content) {
                        return $content;
                    });
                }
            }
            catch (\Exception $e) {
                echo $e->getMessage();
                die();
            }
        }
        else {
            $result = Cache::get($redisKey);
        }

        dump($result);

        return view('blog', [
            'result' => $result,
        ]);
    }

    public function blogRead(Request $request)
    {
        if (!$request->source || !$request->urlPath) abort(404);

        $result = [];

        $urlApi = 'http://localhost:8080/getPost?source='.$request->source.'&urlPath='.$request->urlPath;

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $client = new \GuzzleHttp\Client([
            'headers' => $headers,
        ]);

        try {
            $response = $client->request('GET', $urlApi);

            $statusCode = $response->getStatusCode();
            $content = json_decode($response->getBody());

            $result = $content;

            if ($statusCode == 200 && $content->status) {
                //
            } else {
                die('Tidak ada data output dari API');
            }
        }
        catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }

        dump($result);

        return view('blogDetail', [
            'result' => $result,
        ]);
    }

}

