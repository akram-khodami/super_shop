<div class="bg-white rounded-3xl border border-slate-200 shadow-sm mt-10 p-8 hover:shadow-md transition-shadow duration-300">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
            {{ __('messages.product_attributes') }}
        </h2>
        <div class="h-px flex-1 ml-4 bg-gradient-to-r from-slate-200 to-transparent"></div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">

        <!-- Add attribute form -->
        <form method="post" action="{{ route('admin.products.attributes.store', $product) }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('messages.attribute') }}
                </label>
                <select name="attribute_id" class="w-full rounded-xl border-slate-200">
                    <option value="">{{ __('messages.select_attribute') }}</option>
                    @foreach($availableAttributes as $attribute)
                        <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('messages.attribute_value') }}
                </label>
                <input type="text"
                       name="value"
                       placeholder="{{ __('messages.value_placeholder') }}"
                       class="w-full rounded-xl border-slate-200">
            </div>

            <!-- Checkbox for is_variant -->
            <div class="flex items-center gap-3">
                <input type="checkbox"
                       name="is_variant"
                       id="is_variant"
                       value="1"
                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_variant" class="text-sm text-slate-700">
                    {{ __('messages.is_variant') }}
                    <span class="text-xs text-slate-400 block">{{ __('messages.is_variant_hint') }}</span>
                </label>
            </div>

            <button type="submit"
                    class="w-full py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white hover:from-indigo-700 hover:to-violet-700 transition">
                {{ __('messages.save_attribute') }}
            </button>
        </form>

        <!-- Attributes table -->
        <div class="overflow-hidden rounded-xl border border-slate-200">
            <table class="w-full">
                <thead class="bg-slate-50 border-b-2 border-slate-200">
                <tr>
                    <th class="text-right px-4 py-3 text-sm font-semibold">{{ __('messages.attribute') }}</th>
                    <th class="text-right px-4 py-3 text-sm font-semibold">{{ __('messages.attribute_values') }}</th>
                    <th class="text-center px-4 py-3 text-sm font-semibold">{{ __('messages.actions') }}</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($productAttributes as $productAttribute)
                    <tr class="hover:bg-slate-50 {{ $productAttribute['is_variant'] ? 'bg-amber-50/30' : '' }}">
                        <td class="px-4 py-3 text-sm font-medium">
                            <div class="flex items-center gap-2">
                                {{ $productAttribute['name'] }}
                                @if($productAttribute['is_variant'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            {{ __('messages.variant') }}
                                        </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ __('messages.descriptive') }}
                                        </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($productAttribute['values'] as $value)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg
                                            {{ $productAttribute['is_variant']
                                                ? 'bg-amber-50 text-amber-700 border border-amber-200'
                                                : 'bg-blue-50 text-blue-700 border border-blue-200' }}
                                        text-xs font-medium">
                                            {{ $value['value'] }}
                                            <button onclick="deleteValue({{ $value['id'] }})"
                                                    class="hover:text-red-500 transition-colors"
                                                    aria-label="{{ __('messages.delete') }}">
                                                ×
                                            </button>
                                        </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button onclick="deleteAttribute({{ $productAttribute['id'] }})"
                                    class="text-slate-400 hover:text-red-500"
                                    aria-label="{{ __('messages.delete') }}">
                                🗑️
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-slate-400">
                            {{ __('messages.no_attributes') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.deleteValue = function(valueId) {
            if (!confirm('{{ __('messages.confirm_delete') }}')) {
                return;
            }

            // Your delete logic here
            console.log('Delete value:', valueId);
        };

        window.deleteAttribute = function(attributeId) {
            if (!confirm('{{ __('messages.confirm_delete') }}')) {
                return;
            }

            // Your delete logic here
            console.log('Delete attribute:', attributeId);
        };
    </script>
@endpush
