<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Informações sobre: {{ $client->getFullName() }}
            </h2>
            @if ($client->avatar)
                <td class="px-2 py-4">
                    <img class="rounded-full w-16 sm:w-12 md:w-16" alt="{{ $client->name . 'avatar' }}"
                        src="{{ $client->getAvatarUrl() }}">
                </td>
            @else
                <td class="px-2 py-4">
                    <img class="w-16 sm:w-12 md:w-16" alt="{{ $client->name . 'avatar' }}"
                        src="/img/avatardefault.svg">
                </td>
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
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="w-full rounded px-2 py-4 bg-gray-900">Nome: {{ $client->name }}</p>
                    <p class="mt-5 w-full rounded px-2 py-4 bg-gray-900">Sobrenome: {{ $client->last_name }}</p>
                    <p class="mt-5 w-full rounded px-2 py-4 bg-gray-900">Email: {{ $client->email }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
