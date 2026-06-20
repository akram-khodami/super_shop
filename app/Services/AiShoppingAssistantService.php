<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

class AiShoppingAssistantService
{
    public function extractFilters(string $message): array
    {
        try {
            $currentLocale = App::getLocale();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openrouter.key'),
                'Content-Type' => 'application/json',
                'User-Agent' => 'YourShopApp/1.0',
            ])
                ->timeout(30)
                ->retry(3, 100)
                ->post(
                    'https://openrouter.ai/api/v1/chat/completions',
                    [
                        'model' => 'openrouter/free',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $this->systemPrompt($currentLocale),
                            ],
                            [
                                'role' => 'user',
                                'content' => $message,
                            ],
                        ],
                    ]
                );

            if (!$response->successful()) {
                Log::error('OpenRouter Error Response: ' . $response->body());
                throw new \Exception('AI request failed');
            }

            $content = data_get(
                $response->json(),
                'choices.0.message.content'
            );

            if (empty($content)) {
                return ['search' => $message];
            }

            $filters = json_decode($content, true) ?? [];

            // Ensure search field exists
            if (empty($filters['search'])) {
                $filters['search'] = $message;
            }

            return $filters;

        } catch (\Exception $e) {
            Log::error('OpenRouter error: ' . $e->getMessage());
            return ['search' => $message];
        }
    }

    private function systemPrompt(string $locale): string
    {
        if ($locale === 'en') {
            return $this->englishPrompt();
        }

        // Persian (default)
        return $this->persianPrompt();
    }

    private function persianPrompt(): string
    {
        return '
شما یک دستیار استخراج فیلترهای جستجوی خرید هستید. پیام کاربر را تحلیل کنید و فیلترهای مناسب را استخراج کنید.

قوانین مهم:
1. **زبان جستجو را دقیقاً به همان زبانی که کاربر نوشته حفظ کنید**
2. **هیچ‌گاه کلمات را ترجمه نکنید** - اگر کاربر فارسی نوشته، فارسی بماند، اگر انگلیسی نوشته، انگلیسی بماند
3. **نام برندها و محصولات را به همان زبان اصلی حفظ کنید**
4. فقط فیلترهای زیر را استخراج کنید:
   - "search": عبارت جستجوی اصلی (دقیقاً به همان زبان کاربر)
   - "brand": نام برند (به همان زبان کاربر)
   - "category": دسته‌بندی محصول (به همان زبان کاربر)
   - "max_price": حداکثر قیمت به ریال
   - "min_price": حداقل قیمت به ریال
   - "color": رنگ محصول (به همان زبان کاربر)

مثال‌ها:

کاربر: "قیمت گوشی سامسونگ A55 چنده؟"
پاسخ: {"brand":"سامسونگ","category":"گوشی","search":"گوشی سامسونگ A55","max_price":null,"min_price":null,"color":null}

کاربر: "Samsung A55 price?"
پاسخ: {"brand":"Samsung","category":"phone","search":"Samsung A55","max_price":null,"min_price":null,"color":null}

کاربر: "ربات جاروبرقی شیائومی زیر ۵ میلیون"
پاسخ: {"brand":"شیائومی","category":"جاروبرقی","search":"ربات جاروبرقی شیائومی","max_price":5000000,"min_price":null,"color":null}

کاربر: "Xiaomi robot vacuum under 5 million"
پاسخ: {"brand":"Xiaomi","category":"vacuum","search":"Xiaomi robot vacuum","max_price":5000000,"min_price":null,"color":null}

کاربر: "لباس مجلسی مشکی زنانه"
پاسخ: {"brand":null,"category":"لباس مجلسی","search":"لباس مجلسی مشکی زنانه","max_price":null,"min_price":null,"color":"مشکی"}

کاربر: "Women black formal dress"
پاسخ: {"brand":null,"category":"formal dress","search":"Women black formal dress","max_price":null,"min_price":null,"color":"black"}

اکنون پیام کاربر را پردازش کنید و فقط JSON معتبر برگردانید. زبان جستجو را دقیقاً به همان زبانی که کاربر نوشته حفظ کنید.
';
    }

    private function englishPrompt(): string
    {
        return '
You are a shopping filter extraction assistant. Analyze the user message and extract appropriate filters.

Important rules:
1. **Keep search language exactly as user wrote it**
2. **Never translate words** - keep Persian as Persian, English as English
3. **Keep brand and product names in their original language**
4. Extract only these filters:
   - "search": main search term (exactly in user language)
   - "brand": brand name (in user language)
   - "category": product category (in user language)
   - "max_price": maximum price in Rials
   - "min_price": minimum price in Rials
   - "color": product color (in user language)

Examples:

User: "قیمت گوشی سامسونگ A55 چنده؟"
Response: {"brand":"سامسونگ","category":"گوشی","search":"گوشی سامسونگ A55","max_price":null,"min_price":null,"color":null}

User: "Samsung A55 price?"
Response: {"brand":"Samsung","category":"phone","search":"Samsung A55","max_price":null,"min_price":null,"color":null}

User: "ربات جاروبرقی شیائومی زیر ۵ میلیون"
Response: {"brand":"شیائومی","category":"جاروبرقی","search":"ربات جاروبرقی شیائومی","max_price":5000000,"min_price":null,"color":null}

User: "Xiaomi robot vacuum under 5 million"
Response: {"brand":"Xiaomi","category":"vacuum","search":"Xiaomi robot vacuum","max_price":5000000,"min_price":null,"color":null}

Now process the user message and return only valid JSON. Keep search language exactly as user wrote it.
';
    }
}
