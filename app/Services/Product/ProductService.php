<?php

namespace App\Services\Product;

use App\Exceptions\CannotDeleteProductWithVariantsException;
use App\Exceptions\ProductIsNotTrashedException;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(protected ProductImageService $productImageService) {}

    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {

            $images = $this->prepareData($data);

            $product = Product::create($data);

            $this->productImageService->uploadMany($product, $images);

            return $product;
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {

            $images = $this->prepareData($data);

            $product->update($data);

            $product->attributes()->sync(
                $data['attributes'] ?? []
            );

            $this->productImageService->uploadMany($product, $images);

            return $product->fresh();
        });
    }

    public function delete(Product $product): void
    {
        if ($product->variants()->exists()) {
            throw new CannotDeleteProductWithVariantsException();
        }

        $product->delete(); // soft delete
    }

    private function prepareData(array &$data): array
    {
        $images = $data['images'] ?? [];

        unset($data['images']);

        $data['slug'] = Product::generateUniqueSlug($data['name']);

        return $images;
    }

    public function restore(Product $product): Product
    {
        if (!$product->trashed()) {
            throw new ProductIsNotTrashedException();
        }

        $product->restore();
        return $product->fresh();
    }

    public function getPaginatedList(array $validatedData): LengthAwarePaginator
    {
        $products = Product::query()
            ->with(['category', 'brand', 'thumbnail'])
            ->filter($validatedData)
            ->paginate(15)
            ->withQueryString();

        return $products;
    }

    public function getRelatedProducts(Product $product, int $limit = 4): Collection
    {
        return Product::query()
            ->active()
            ->whereCategoryId($product->category_id)
            ->whereKeyNot($product->id)
            ->with('thumbnail')
            ->limit($limit)
            ->get();
    }

    public function loadProductDetails(Product $product): Product
    {
        return $product->load([
            'images',
            'brand',
            'category',
            'variants.variantAttributeValue.productAttributeValue',
            'variants.images',
            'productAttributes.attribute',
            'productAttributes.values',
        ]);
    }

    public function paginateActiveProducts(int $perPage = 12)
    {
        return Product::query()
            ->active()
            ->with(['thumbnail', 'brand', 'variants'])
            ->latest()
            ->paginate($perPage);
    }
}
