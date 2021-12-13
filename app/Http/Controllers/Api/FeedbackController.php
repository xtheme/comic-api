<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeedbackQuestion;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class FeedbackController extends Controller
{
    /**
     * 查询廣告位底下的廣告列表
     */
    public function questionnaire()
    {
        $cache_key = 'questionnaire';

        $data = Cache::remember($cache_key, 28800, function () {
            $questions = FeedbackQuestion::with('options')->get();

            return $questions->map(function ($question) {
                $options = $question->options->mapWithKeys(function ($option) {
                    return [$option->id => $option->option];
                })->toArray();

                return [
                    'id' => $question->id,
                    'question' => $question->question,
                    'options' => $options,
                ];
            })->toArray();
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
