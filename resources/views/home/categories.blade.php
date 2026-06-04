{{--categories.blade.php--}}
<section class="py-16">

    <div class="max-w-7xl mx-auto px-4">

        <h2 class="text-3xl font-bold mb-10">
            Featured Categories
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

            @foreach($categories as $category)

                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition">

                    <div class="text-4xl mb-4">
                        @if($category->image)
                            <img
                                src="{{ asset('storage/'.$category->image) }}"
                                class="rounded object-cover"
                            >
                        @else
                            📦
                        @endif
                    </div>

                    <h3 class="font-semibold">
                        {{$category->name}}
                    </h3>

                </div>

            @endforeach

        </div>

    </div>

</section>
