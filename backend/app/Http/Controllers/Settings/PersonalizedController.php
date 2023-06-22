<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\ResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PersonalizedController extends ResponseController
{
    
    public function fetchallSources()
    {
        $allSources = [];
        try {
            $newsorg_Key = env('NEWSORG_APIKEY');
            $apiKey = "?apiKey=$newsorg_Key";
            $fetch = Http::get("https://newsapi.org/v2/top-headlines/sources" . $apiKey);
            foreach ($fetch['sources'] as $key => $value) {
                $allSources[] = [
                    'id' => $value['id'],
                    'name' => $value['name'],
                ];
            }
            return $this->sendSuccessResponse($allSources,'Success', 201);
        } catch (\Throwable $e) {
            return $this->sendErrorResponse('Error while fetching Data:', $e->getMessage(), 500);
        }
    }
}
