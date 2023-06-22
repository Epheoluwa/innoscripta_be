<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseController;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ArticleController extends ResponseController
{
    private $allArticlesArray = [];

    public function fetchArticles(Request $request)
    {
        $search = $request->q === 'null' ? '' : $request->q;
        $from = $request->from === 'null' ? '2023-01-01' : $request->from;
        $to = $request->to === 'null' ? Carbon::now()->format('Y-m-d') : $request->to;
        $category = $request->category === 'null' ? '' : $request->category;


        // return response()->json($from);
        // exit;


        $this->fetchNewsOrg($search, $category);
        $this->fetchNewYorkTimes($search, $from, $to);

        shuffle($this->allArticlesArray);

        return response()->json($this->allArticlesArray);
        exit;
        $this->fetchNewYorkTimes($search, $from, $to);
        $this->fetchNewsOrg($search, $category);
        shuffle($this->allArticlesArray);

        return response()->json($this->allArticlesArray);
    }


    private function fetchNewsOrg($search, $category)
    {
        try {

            $newsorg_Key = env('NEWSORG_APIKEY');
            $apiKey = "&apiKey=$newsorg_Key";
            $fetch = Http::get("https://newsapi.org/v2/top-headlines?q=$search&category=$category" . $apiKey);


            foreach ($fetch['articles'] as $key => $value) {
                $this->allArticlesArray[] = [
                    'Main_source' => 'NewsOrg',
                    'Title' => $value['title'],
                    'Description' => $value['description'],
                    'Source' => $value['source']['name'],
                    'Author' => $value['author'],
                    'Published_date' => Carbon::parse($value['publishedAt'])->format('Y-m-d'),
                ];
            }
        } catch (\Throwable $e) {
            return $this->sendErrorResponse('Error while fetching Data:', $e->getMessage(), 500);
        }
    }
    private function fetchNewYorkTimes($search, $from, $to)
    {
        try {
            $nytimes_Key = env('NYTIMES_APIKEY');
            $apiKey = "&api-key=$nytimes_Key";
            $fetch = Http::get("https://api.nytimes.com/svc/search/v2/articlesearch.json?q=$search&begin_date=$from&end_date=$to" . $apiKey);

            foreach ($fetch['response']['docs'] as $key => $value) {
                $this->allArticlesArray[] = [
                    'Main_source' => 'NewYorkTimes',
                    'Title' => $value['headline']['print_headline'],
                    'Description' => $value['lead_paragraph'],
                    'Source' => $value['source'],
                    'Author' => $value['byline']['original'],
                    'Published_date' => Carbon::parse($value['pub_date'])->format('Y-m-d'),
                ];
            }
        } catch (\Throwable $e) {
            return $this->sendErrorResponse('Error while fetching Data:', $e->getMessage(), 500);
        }
    }
}
