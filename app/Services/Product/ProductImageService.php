<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageService
{
    public function delete(ProductImage $image): void
    {
        //Todo:if is_primary = true,the next image become primary(if exist)

        Storage::disk('public')->delete($image->image);

        $image->delete();
    }

    public function uploadMany(Product $product, array $images): void
    {
        if (empty($images)) {
            return;
        }

        $storedPaths = [];

        $sortOrder = ($product->images()->max('sort_order') ?? -1) + 1;

        try {

            $records = [];

            foreach ($images as $image) {

                $path = $image->store('products', 'public');

                $storedPaths[] = $path;

                $records[] = [
                    'image' => $path,
                    'sort_order' => $sortOrder++,
                ];
            }

            $product->images()->createMany($records);
        } catch (\Throwable $e) {

            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $e;
        }
    }
}
