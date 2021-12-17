<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\FeedbackQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class FeedbackController extends Controller
{
    /**
     * 意見反饋問卷
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

    /**
     * 意見反饋問卷
     */
    public function add(Request $request)
    {
        $user_id = $request->user()->id;
        $fingerprint = $request->header('uuid') ?? '';
        $option_ids = $request->input('option_ids');

        $exists = Feedback::where('user_id', $user_id)->orWhere('fingerprint', $fingerprint)->exists();

        if ($exists) {
            return Response::jsonError('很抱歉，收藏项目不存在或已下架！');
        }

        $feedback = new Feedback;
        $feedback->user_id = $request->user()->id;
        $feedback->fingerprint = $request->header('uuid');

        return Response::jsonSuccess(__('api.success'), $option_ids);
    }
}
