<div
    class="bg-white rounded-3xl border border-slate-200 shadow-sm mt-10 p-8 hover:shadow-md transition-shadow duration-300">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
            {{__('messages.product_attributes')}}
        </h2>
        <div class="h-px flex-1 ml-4 bg-gradient-to-r from-slate-200 to-transparent"></div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">

        <!-- add attribute form -->
        <form method="post" action="{{ route('admin.products.attributes.store', $product) }}" class="space-y-4">
            @csrf

            <div>
                <label>ویژگی</label>
                <select name="attribute_id" class="w-full rounded-xl border-slate-200">
                    <option value="">-- انتخاب ویژگی --</option>
                    @foreach($availableAttributes as $attribute)
                        <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>مقدار</label>
                <input type="text" name="value" placeholder="مثال: قرمز، XL"
                       class="w-full rounded-xl border-slate-200">
            </div>

            <!-- checkbox برای تنوع‌ساز بودن -->
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_variant" id="is_variant" value="1"
                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_variant" class="text-sm text-slate-700">
                    این ویژگی تنوع محصول است
                    <span class="text-xs text-slate-400 block">(مثل رنگ، سایز و...)</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white">
                ذخیره ویژگی
            </button>
        </form>
        <!-- جدول ویژگی‌های ثبت‌شده -->
        <div class="overflow-hidden rounded-xl border border-slate-200">
            <table class="w-full">
                <thead class="bg-slate-50 border-b-2 border-slate-200">
                <tr>
                    <th class="text-right px-4 py-3 text-sm font-semibold">ویژگی</th>
                    <th class="text-right px-4 py-3 text-sm font-semibold">مقادیر</th>
                    <th class="text-center px-4 py-3 text-sm font-semibold">عملیات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($productAttributes as $productAttribute)
                    <tr class="hover:bg-slate-50 {{ $productAttribute['is_variant'] ? 'bg-amber-50/30' : '' }}">
                        <td class="px-4 py-3 text-sm font-medium">
                            <div class="flex items-center gap-2">
                                {{ $productAttribute['name'] }}
                                @if($productAttribute['is_variant'])
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            تنوع
                        </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            توصیفی
                        </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($productAttribute['values'] as $value)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg
                                   {{ $productAttribute['is_variant'] ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}
                                        text-xs font-medium">
                            {{ $value['value'] }}
                            <button onclick="deleteValue({{ $value['id'] }})"
                                    class="hover:text-red-500 transition-colors">
                                ×
                            </button>
                        </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button onclick="deleteAttribute({{ $productAttribute['id'] }})"
                                    class="text-slate-400 hover:text-red-500">
                                🗑️
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-slate-400">
                            ویژگی‌ای ثبت نشده است
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
