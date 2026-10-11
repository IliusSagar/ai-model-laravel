<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GroqService
{
    public function generate(string $prompt): string
    {
        $apiKey = config('services.groq.key');

        if (! $apiKey) {
            throw new RuntimeException(
                'GROQ_API_KEY is missing from .env.'
            );
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(60)
            ->post(config('services.groq.url'), [
                'model' => config('services.groq.model'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful AI assistant. Answer clearly.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'max_completion_tokens' => 500,
            ]);

        if ($response->failed()) {
            logger()->error('Groq API request failed', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            throw new RuntimeException(
                'Groq API request failed. Check the API key, model, and account limits.'
            );
        }

        $result = $response->json(
            'choices.0.message.content'
        );

        if (! is_string($result) || trim($result) === '') {
            throw new RuntimeException(
                'The AI returned an empty response.'
            );
        }

        return $result;
    }
}