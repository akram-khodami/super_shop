<header class="bg-white border-b px-6 py-4">

    <div class="flex justify-between items-center">

        <h1 class="font-semibold text-lg">
            {{__('messages.administration')}}
        </h1>

        <div>

            {{ auth()->user()->name }}

        </div>

    </div>

</header>
