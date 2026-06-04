{{--products.blade.php--}}
<section class="bg-white py-16">

    <div class="max-w-7xl mx-auto px-4">

        <div class="flex justify-between items-center mb-10">

            <h2 class="text-3xl font-bold">
                New Products
            </h2>

            <a href="{{route('products.index')}}" class="text-indigo-600">
                View All
            </a>

        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">

            @foreach($products as  $product)
                <a href="{{ route('products.show',$product) }}">
                    <x-product-card :product="$product"/>
                </a>
            @endforeach

        </div>

    </div>

</section>
