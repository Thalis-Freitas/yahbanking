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
            <div class="bg-green-700 text-white p-4 rounded font-bold mb-10 mx-6 sm:mx-0">
                {{ session('msg') }}
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-700 text-white p-4 rounded font-bold mb-10 mx-6 sm:mx-0">
                Não foi possível processar a solicitação, por favor verifique o campo e tente novamente.
            </div>
            @endif
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg
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
                            Valor total: <span class="text-cyan-700"> R${{ $client->total_value }}</span>
                        </p>
                        <p class="w-full mt-2 p-4 bg-white font-bold">
                            Valor não investido: <span class="text-red-700"> R${{ $client->uninvested_value }}</span>
                        </p>
                        <p class="w-full mt-2 p-4 bg-white font-bold rounded-b-lg break-all">
                            Valor investido: <span class="text-green-700"> R${{ $client->invested_value }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <h2 class="px-6 mt-4 sm:mt-8 mb-4 text-2xl font-bold text-gray-400">
                    Depositar valor
                </h2>
                <form method="POST" class="mb-6 mx-6"
                action="{{ route('clients.deposit', $client->id) }}">
                @csrf
                @method("PATCH")
                    <input type="text" class="rounded mb-2 me-2" name="uninvested_value"
                        placeholder="Insira o valor aqui">
                    <button type="submit" class="px-4 py-2 text-center shadow
                        text-white font-bold bg-emerald-700 hover:bg-emerald-900 rounded
                        transition ease-in-out duration-500">
                        Fazer depósito
                    </button>
                    @error('uninvested_value')
                        <p class="text-red-600 mb-3"> {{ $message }} </p>
                    @enderror
                </form>
            </div>
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <h2 class="px-6 mt-4 sm:mt-8 mb-4 text-2xl font-bold text-gray-400">
                    Associar cliente a um investimento
                </h2>
                <form method="POST" class="mb-6 mx-6"
                action="{{ route('clients.investiment', $client->id) }}">
                @csrf
                @method("POST")
                    <label for="investiment" class="block text-white mb-2">Investimento</label>
                    <select name="investiment" class="rounded mb-6">
                        @foreach ($investiments as $investiment)
                            <option value="{{ $investiment }}" @selected(old('investiment') == $investiment)>
                                {{ $investiment->getAbbreviationAndName() }}
                            </option>
                        @endforeach
                    </select>
                    <label for="invested_value" class="block text-white mb-2">Valor a ser investido</label>
                    <input type="text" class="rounded mb-2 me-2" name="invested_value">
                    <button type="submit" class="px-4 py-2 text-center shadow
                        text-white font-bold bg-emerald-700 hover:bg-emerald-900 rounded
                        transition ease-in-out duration-500">
                        Investir
                    </button>
                    @error('invested_value')
                        <p class="text-red-600 mb-3"> {{ $message }} </p>
                    @enderror
                </form>
            </div>
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <h2 class="px-6 mt-4 sm:mt-8 mb-4 text-2xl font-bold text-gray-400">
                    Investimentos deste cliente
                </h2>
                @if ($client->investiments->isNotEmpty())
                    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl p-4 rounded">
                        @foreach ($client->investiments as $investiment)
                            <div class="bg-white rounded py-4 px-4 m-4 relative">
                                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-700">
                                    {{ $investiment->getAbbreviationUpper() }}
                                </h2>
                                <p class="text-gray-600 mb-4">
                                    Nome Comercial: {{ $investiment->name }}
                                </p>
                                <a href="{{ route('investiments.show', $investiment->id) }}"
                                    class="px-4 py-2 text-center shadow text-white font-bold
                                        bg-cyan-700 hover:bg-cyan-900 rounded
                                        transition ease-in-out duration-500 md:me-2">
                                    Ver detalhes
                                </a>
                                <p class="text-gray-600 mt-4 pt-4 border-t-2">
                                    Valor Investido:
                                    <span class="text-green-700 font-bold">
                                        R${{ $investiment->pivot->invested_value }}
                                    </span>
                                </p>
                                <div class="flex flex-col md:right-px w-full md:flex-row">
                                    <form method="POST" class="mt-4"
                                        action="{{ route('clients.investiment.apply', $client->id) }}">
                                        @csrf
                                        @method("POST")
                                        <label for="invested_value" class="block mb-2 text-gray-600">Aplicar novos valores</label>
                                        <input type="text" class="rounded mb-2 me-2" name="invested_value"
                                            placeholder="Insira o valor aqui">
                                        <input type="text" class="hidden" name="investiment_id"
                                            value="{{ $investiment->id }}">
                                        <button type="submit" class="px-4 py-2 text-center shadow
                                            text-white font-bold bg-emerald-700 hover:bg-emerald-900 rounded
                                            transition ease-in-out duration-500">
                                            Aplicar
                                        </button>
                                        @error('invested_value')
                                            <p class="text-red-600 mb-3"> {{ $message }} </p>
                                        @enderror
                                    </form>
                                    <form method="POST" class="mt-4 md:ms-8"
                                        action="{{ route('clients.investiment.redeem', $client->id) }}">
                                        @csrf
                                        @method("POST")
                                        <label for="invested_value" class="block mb-2 text-gray-600">Resgatar valores</label>
                                        <input type="text" class="rounded mb-2 me-2" name="invested_value"
                                            placeholder="Insira o valor aqui">
                                        <input type="text" class="hidden" name="investiment_id"
                                            value="{{ $investiment->id }}">
                                        <button type="submit" class="px-4 py-2 text-center shadow
                                            rounded bg-gray-300 text-red-700 hover:bg-red-600 hover:text-white
                                            transition ease-in-out duration-500">
                                            Resgatar
                                        </button>
                                        @error('invested_value')
                                            <p class="text-red-600 mb-3"> {{ $message }} </p>
                                        @enderror
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 m-4 font-bold bg-amber-500 text-white font-bold rounded">
                        Nenhum investimento encontrado!
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
