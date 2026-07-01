<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Services\Product\ProductImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class ProductImageController extends Controller
{
    public function __construct(
        protected ProductImageService $imageService,
    ) {}

    public function destroy(ProductImage $image): RedirectResponse
    {
        Gate::authorize('delete', $image);

        $this->imageService->delete($image);

        return back()->with(
            'success',
            __('messages.image_deleted_successfully')
        );
    }
}
