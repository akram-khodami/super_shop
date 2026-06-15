<?php

namespace App\Rules;

use App\Models\Product;
use App\Models\VariantAttributeValue;
use Closure;
use Illuminate\Contracts\Validation\ImplicitRule;

class UniqueVariant implements ImplicitRule
{
    private Product $product;
    private ?int $ignoreVariantId;
    private string $message = 'این تنوع قبلاً برای این محصول ثبت شده است.';

    public function __construct(Product $product, ?int $ignoreVariantId = null)
    {
        $this->product = $product;
        $this->ignoreVariantId = $ignoreVariantId;
    }

    public function passes($attribute, $value): bool
    {
        $productAttributeValueId = $value ?: null;

        return !$this->variantExists($productAttributeValueId);
    }

    public function message(): string
    {
        return $this->message;
    }

    private function variantExists(?int $productAttributeValueId): bool
    {
        // حالت ۱: محصول ساده (بدون تنوع)
        if (is_null($productAttributeValueId) || empty($productAttributeValueId)) {
            $query = $this->product->variants();

            if ($this->ignoreVariantId) {
                $query->where('id', '!=', $this->ignoreVariantId);
            }

            return $query->exists();
        }

        // حالت ۲: محصول متغیر (با تنوع)
        $query = VariantAttributeValue::where('product_attribute_value_id', $productAttributeValueId)
            ->whereHas('variant', function ($q) {
                $q->where('product_id', $this->product->id);

                if ($this->ignoreVariantId) {
                    $q->where('variant_id', '!=', $this->ignoreVariantId);
                }
            });

        return $query->exists();
    }
}
