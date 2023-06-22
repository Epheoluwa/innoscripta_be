<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\ResponseController;
use App\Models\UserData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

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

    public function postPersonalisedData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sources' => 'required',
            'category' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendErrorResponse('Validation failed.', $validator->errors()->first(), 422);
        }

        $data = [
            'user_id' => Auth::user()->id,
            'sources' => $request->sources,
            'category' => $request->category,
        ];

        $save = UserData::updateOrCreate(['user_id' => Auth::user()->id], $data);

        return $this->sendSuccessResponse($save,'Personalised Data Created Successfully', 201);
    }
}
