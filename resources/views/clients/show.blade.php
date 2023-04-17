<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight break-word">
                Informações sobre: {{ $client->getFullName() }}
            </h2>
            @if ($client->avatar)
                <img class="rounded-full w-16 h-16" alt="{{ $client->name . 'avatar' }}"
                    src="{{ $client->getAvatarUrl() }}">
            @else
                <img class="w-16" alt="{{ $client->name . 'avatar' }}"
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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg
                        flex flex-col sm:flex-row">
                <div class="sm:min-w-[50%]">
                    <h2 class="px-6 mt-8 text-2xl text-gray-400 font-bold">
                        Dados cadastrais
                    </h2>
                    <div class="p-6 text-gray-900 font-bold">
                        <p class="w-full p-4 bg-white rounded-t-lg">
                            Nome: <span class="font-medium">{{ $client->name }} </span>
                        </p>
                        <p class="w-full mt-2 p-4 bg-white">
                            Sobrenome: <span class="font-medium">{{ $client->last_name }} </span>
                        </p>
                        <p class="w-full mt-2 p-4 bg-white rounded-b-lg break-all">
                            Email: <span class="font-medium">{{ $client->email }} </span>
                        </p>
                    </div>
                </div>
                <div class="sm:min-w-[50%]">
                    <h2 class="px-6 mt-4 sm:mt-8 text-2xl font-bold text-gray-400">
                        Valores
                    </h2>
                    <div class="p-6 text-gray-900 font-bold">
                        <p class="w-full p-4 bg-white font-bold rounded-t-lg">
                            Valor total: <span class="text-blue-700"> R${{ $client->total_value }}</span>
                        </p>
                        <p class="w-full mt-2 p-4 bg-white font-bold">
                            Valor não investido: <span class="text-red-700"> R${{ $client->uninvested_value }}</span>
                        </p>
                        <p class="w-full mt-2 p-4 bg-white font-bold rounded-b-lg break-all">
                            Valor investido: <span class="text-green-700"> R${{ $client->invested_value }}</span>
                        </p>
                    </div>
                    <h2 class="px-6 mt-4 sm:mt-8 mb-4 text-2xl font-bold text-gray-400">
                        Depositar valor
                    </h2>
                    <form method="POST" class="mb-6 mx-6"
                    action="{{ route('clients.deposit', $client->id) }}">
                    @csrf
                    @method("PATCH")
                        @error('uninvested_value')
                            <p class="text-red-600 mt-3"> {{ $message }} </p>
                        @enderror
                        <input type="text" class="rounded mb-2 me-2" name="uninvested_value"
                            placeholder="Insira o valor aqui">
                        <button type="submit" class="px-4 py-2 text-center shadow
                            text-white font-bold bg-emerald-700 hover:bg-emerald-900 rounded
                            transition ease-in-out duration-500">
                            Fazer depósito
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
