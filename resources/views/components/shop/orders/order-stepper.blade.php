@php
    $currentIndex = collect($steps())
        ->search(
            fn($step) =>
                $step['status'] === $order->status
        );
@endphp

<div class="mb-8 bg-white border rounded-2xl p-6 shadow-sm">

    <div class="flex items-center justify-between">

        @foreach($steps() as $index => $step)

            @php
                $completed = $index <= $currentIndex;
            @endphp

            <div class="flex flex-col items-center flex-1">

                <div
                    class="
                        w-10 h-10
                        rounded-full
                        flex items-center justify-center
                        text-sm font-bold

                        {{ $completed
                            ? 'bg-green-600 text-white'
                            : 'bg-gray-200 text-gray-500'
                        }}
                        "
                >
                    @if($completed)
                        ✓
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>

                <span class="mt-2 text-sm text-center">
                    {{ $step['title'] }}
                </span>

            </div>

            @if(!$loop->last)

                <div
                    class="
                        h-1 flex-1 mx-2

                        {{ $index < $currentIndex
                            ? 'bg-green-600'
                            : 'bg-gray-200'
                        }}
                        "
                ></div>

            @endif

        @endforeach

    </div>

</div>
