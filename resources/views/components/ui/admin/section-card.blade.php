@props([
'title' => 'Title',
'subtitle' => 'Sub Title',
'addButtonUrl' => '/',
'addButtonTitle' => 'Add',
])

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-md shadow-slate-100/50 mt-8 transition-all">

    <div class="px-6 py-5 border-b border-slate-100">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

            <div>
                <h2 class="text-lg font-semibold tracking-tight text-slate-800">
                    {{ $title }}
                </h2>

                @if($subtitle)
                    <p class="text-slate-400 text-sm mt-0.5">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            <div class="hidden lg:block h-6 w-px bg-slate-200"></div>

            <a href="{{ $addButtonUrl }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2 text-sm font-medium rounded-xl
                      bg-gradient-to-r from-indigo-600 to-indigo-500 text-white
                      hover:from-indigo-700 hover:to-indigo-600
                      shadow-sm hover:shadow-md transition-all duration-200 ease-out
                      active:scale-[0.97]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ $addButtonTitle }}
            </a>

        </div>

    </div>

    <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-slate-200">
        {{ $slot }}
    </div>

</div>
