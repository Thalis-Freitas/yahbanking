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
            <div class="bg-green-700 text-white p-4 rounded font-bold mb-10 mx-6 sm:mx-0">
                {{ session('msg') }}
            </div>
            @endif
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
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
                <h2 class="px-8 mt-8 text-2xl font-bold text-gray-400">
                    Clientes deste investimento
                </h2>
                @if ($investiment->clients->isNotEmpty())
                    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-md">
                        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-md p-4 rounded">
                            @foreach ($investiment->clients as $client)
                                <div class="bg-white rounded p-4 m-4 md:relative">
                                    <div class="flex items-center">
                                        @if ($client->avatar)
                                            <img class="rounded-full w-16 h-16" alt="{{ $client->name . 'avatar' }}"
                                                src="{{ $client->getAvatarUrl() }}">
                                        @else
                                            <img class="w-16" alt="{{ $client->name . 'avatar' }}"
                                            src="/img/avatardefault.svg">
                                        @endif
                                        <div class="ms-2 md:max-w-[35%] lg:max-w-[50%] xl:max-w-[60%]">
                                            <h2 class="mt-2 text-2xl font-bold text-gray-700 break-all text-justify">
                                                {{ $client->getFullName() }}
                                            </h2>
                                            <div class="-mt-1 text-justify text-gray-600 break-all text-justify">
                                                {{ $client->email }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 sm:p-0 py-2 my-4 flex flex-col sm:w-full sm:flex-row
                                                top-4 right-4 text-gray-800">
                                        <a href="{{ route('clients.show', $client->id) }}"
                                            class="px-4 py-2 mt-2 text-center shadow text-white font-bold
                                                bg-cyan-700 hover:bg-cyan-900 rounded
                                                transition ease-in-out duration-500 sm:me-2">
                                            Mais informações
                                        </a>
                                        <div class="px-4 py-2 mt-2 text-center shadow font-bold
                                            bg-gray-300 sm:me-2 rounded">
                                            Valor aplicado neste investimento:
                                            <span class="text-green-700">
                                                R${{$client->pivot->invested_value}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="px-4 py-2 my-4 md:mb-0 rounded bg-gray-300 font-bold text-gray-800
                                        sm:flex sm:justify-around">
                                        <div>
                                            Valor total: <span class="text-cyan-700"> R${{ $client->total_value }}</span>
                                        </div>
                                        <div>
                                            Valor não investido: <span class="text-red-700"> R${{ $client->uninvested_value }}</span>
                                        </div>
                                        <div>
                                            Valor investido: <span class="text-green-700"> R${{ $client->invested_value }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-4 mx-6 mt-4 mb-6 font-bold bg-amber-500 text-white font-bold rounded">
                        Nenhum cliente encontrado!
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
