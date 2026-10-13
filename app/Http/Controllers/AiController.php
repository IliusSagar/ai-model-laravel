<?php

namespace App\Http\Controllers;

use App\Services\GroqService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AiController extends Controller
{
    public function generate(
        Request $request,
        GroqService $groq
    ): JsonResponse {
        $validated = $request->validate([
            'prompt' => ['required', 'string', 'max:2000'],
        ]);

        try {
            $result = $groq->generate($validated['prompt']);

            return response()->json([
                'success' => true,
                'result' => $result,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'AI generation failed. Check the Laravel logs.',
            ], 502);
        }
    }
}