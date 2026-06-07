{{-- resources/views/components/gallery.blade.php --}}
@props([
'images',
'title' => 'Product Gallery',
'subtitle' => 'Manage all uploaded images',
'deleteRoute' => null,
'assetPath' => 'storage',
])

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm mt-10 p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">
                {{ $title }}
            </h2>
            <p class="text-sm text-slate-500">
                {{ $subtitle }}
            </p>
        </div>
        <div class="text-sm text-slate-500">
            {{ $images->count() }} Images
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-5">
        @foreach($images as $image)
            <div class="group relative bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition">
                <img
                    src="{{ asset($assetPath.'/'.$image->image) }}"
                    class="w-full h-44 object-cover group-hover:scale-105 transition duration-300"
                >
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                    <form
                        action="{{ $deleteRoute ? route($deleteRoute, $image) : '#' }}"
                        method="POST"
                    >
                        @csrf
                        @method('DELETE')
                        <button
                            onclick="return confirm('Delete image?')"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl shadow"
                        >
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
