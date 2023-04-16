<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight break-word">
                Informações sobre: {{ $client->getFullName() }}
            </h2>
            @if ($client->avatar)
                <img class="rounded-full w-16 sm:w-12 md:w-16" alt="{{ $client->name . 'avatar' }}"
                    src="{{ $client->getAvatarUrl() }}">
            @else
                <img class="w-16 sm:w-12 md:w-16" alt="{{ $client->name . 'avatar' }}"
                    src="/img/avatardefault.svg">
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8a">
            @if(session('msg'))
            <div class="bg-green-700 text-white p-4 rounded font-bold mb-10">
                {{ session('msg') }}
            </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 font-bold">
                    <p class="w-full p-4 bg-white font-bold rounded-t-lg">
                        Nome: <span class="font-medium">{{ $client->name }} </span>
                    </p>
                    <p class="w-full mt-2 p-4 bg-white font-bold">
                        Sobrenome: <span class="font-medium">{{ $client->last_name }} </span>
                    </p>
                    <p class="w-full mt-2 p-4 bg-white font-bold rounded-b-lg break-all">
                        Email: <span class="font-medium">{{ $client->email }} </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
