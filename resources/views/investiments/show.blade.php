<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Informações sobre: {{ $investiment->getAbbreviationUpper() }}
            </h2>
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
                <div class="p-6 text-gray-900">
                    <p class="w-full p-4 bg-white rounded-t-lg font-bold">
                        Sigla: <span class="font-medium">{{ $investiment->getAbbreviationUpper() }}</span>
                    </p>
                    <p class="w-full mt-2 p-4 bg-white font-bold">
                        Nome Comercial: <span class="font-medium">{{ $investiment->name }}</span>
                    </p>
                    <p class="w-full mt-2 p-4 bg-white rounded-b-lg font-bold">
                        Descrição: <span class="font-medium">{{ $investiment->description }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
