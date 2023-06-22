<?php

namespace App\Http\Controllers\New;

use App\Http\Controllers\ResponseController;
use App\Models\UserData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsController extends ResponseController
{
    private $UserNews = [];
    public function getNews()
    {
          $this->getUserNews();
          shuffle($this->UserNews);
        return $this->UserNews;
    }

    private function getUserNews()
    {
        $newsorg_Key = env('NEWSORG_APIKEY');
        $apiKey = "&apiKey=$newsorg_Key";
        $userID = auth()->user()->id;
        $fetchUserData = UserData::where('user_id', $userID)->first();

        try {
            if ($fetchUserData === null) {
                $source = 'bbc-news';
                $fetch = Http::get("https://newsapi.org/v2/top-headlines?sources=$source" . $apiKey);
                foreach ($fetch['articles'] as $all) {
                    $this->UserNews[] = [
                        'Title' => $all['title'],
                        'Description' => $all['description'],
                        'Source' => $all['source']['name'],
                        'Author' => $all['author'],
                        'Published_date' => Carbon::parse($all['publishedAt'])->format('Y-m-d'),
                    ];
                }
            } else {
                $sources = $fetchUserData['sources'];
                foreach ($sources as $source) {
                    $fetch = Http::get("https://newsapi.org/v2/top-headlines?sources=$source" . $apiKey);
                    foreach ($fetch['articles'] as $all) {
                        $this->UserNews[] = [
                            'Title' => $all['title'],
                            'Description' => $all['description'],
                            'Source' => $all['source']['name'],
                            'Author' => $all['author'],
                            'Published_date' => Carbon::parse($all['publishedAt'])->format('Y-m-d'),
                        ];
                    }
                }

                $category = $fetchUserData['category'];
                foreach ($category as $cat) {
                    $fetch = Http::get("https://newsapi.org/v2/top-headlines/sources?category=$cat" . $apiKey);
                    foreach ($fetch['sources'] as $all) {
                        $this->UserNews[] = [
                            'Name' => $all['name'],
                            'Description' => $all['description'],
                            'Category' => $all['category'],
                            'Country' => $all['country'],
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }
}
