@php($addButtonUrl = route('admin.products.variants.create', $product))
<x-ui.admin.section-card :title="__('messages.product_variants')" :subtitle="__('messages.variants_section_subtitle')" :addButtonUrl="$addButtonUrl" :addButtonTitle="__('messages.add_variant')">
    <table class="w-full">

        <thead class="bg-slate-50/80">
            <tr>
                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    {{ __('messages.variant_attribute') }}
                </th>

                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    {{ __('messages.SKU') }}
                </th>

                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    {{ __('messages.price') }}
                </th>

                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    {{ __('messages.sale_price') }}
                </th>

                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    {{ __('messages.stock') }}
                </th>

                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    {{ __('messages.status') }}
                </th>

                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    {{ __('messages.image') }}
                </th>

                <th class="text-right px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    {{ __('messages.actions') }}
                </th>
            </tr>
        </thead>

        <tbody>
            @forelse($product->variants as $variant)
                <tr class="border-t border-slate-100 hover:bg-indigo-50/30 transition-colors duration-150 group">
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-800">
                            {{ $variant->variant_value ?? '—' }}
                        </div>
                    </td>

                    <td class="px-6 py-4 text-slate-500 text-sm">
                        {{ $variant->sku }}
                    </td>

                    <td class="px-6 py-4 font-semibold text-slate-800">
                        {{ number_format($variant->price) }}
                    </td>

                    <td class="px-6 py-4 font-semibold text-slate-800">
                        {{ number_format($variant->sale_price) }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-medium">
                            {{ $variant->stock }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        @if ($variant->is_active)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                {{ __('messages.active') }}
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-red-50 text-red-700 text-xs font-semibold">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                {{ __('messages.inactive') }}
                            </span>
                        @endif
                    </td>

                    <td class="px-4 py-3">
                        @if ($variant->thumbnail_url)
                            <img src="{{ $variant->thumbnail_url }}"
                                class="h-12 w-12 rounded-lg object-cover shadow-sm group-hover:shadow-md transition-shadow">
                        @else
                            <span class="text-slate-400 text-sm">{{ __('messages.no_image') }}</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-1.5">
                            <a href="{{ route('admin.products.variants.edit', [$product, $variant]) }}"
                                class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm transition">
                                {{ __('messages.edit') }}
                            </a>

                            <form method="POST"
                                action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete Variant?')"
                                    class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-sm transition">
                                    {{ __('messages.delete') }}
                                </button>
                            </form>

                            <a href="{{ route('admin.variants.inventory', $variant) }}"
                                class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow-sm transition">
                                {{ __('messages.manage_inventory') }}
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <div class="text-slate-400 text-sm">
                            {{ __('messages.no_records_created_yet') }}
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-ui.admin.section-card>
