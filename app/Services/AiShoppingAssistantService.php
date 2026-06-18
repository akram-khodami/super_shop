<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiShoppingAssistantService
{
    public function extractFilters(string $message): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openrouter.key'),
            'Content-Type' => 'application/json',
        ])
            ->timeout(3000)
            ->post(
                'https://openrouter.ai/api/v1/chat/completions',
                [
                    'model' => 'openrouter/free',

                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $this->systemPrompt(),
                        ],

                        [
                            'role' => 'user',
                            'content' => $message,
                        ],
                    ],
                ]
            );

        if (! $response->successful()) {

            throw new \Exception(
                'AI request failed'
            );
        }

        $content = data_get(
            $response->json(),
            'choices.0.message.content'
        );

        return json_decode(
            $content,
            true
        ) ?? [];
    }

    private function systemPrompt(): string
    {
        return '
Extract shopping filters from user message.

Return ONLY valid JSON.

Example:

{
  "brand": null,
  "category": null,
  "search": null,
  "max_price": null
}
';
            /*  return 'Extract filters.
        Return values exactly as user language.
        Do not translate.
        Return JSON only.
        Example:

{
  "brand": null,
  "category": null,
  "search": null,
  "max_price": null
}

        '*/;
    }
}
