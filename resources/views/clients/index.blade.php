<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight mb-4">
                {{ __('Gerenciamento de Clientes') }}
            </h2>
            <a href="{{route('clients.create')}}"
            class="px-4 py-2 shadow text-white font-bold text-center
                   bg-green-700 hover:bg-green-900 rounded
                   transition ease-in-out duration-500">
                Novo Cliente
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 md:px-8">
            @include('components.success-alert')
            @if ($clients->isNotEmpty())
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-md">
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-md p-4 rounded">
                    @foreach ($clients as $client)
                        <div class="bg-white rounded p-4 m-4 md:relative">
                            <div class="flex items-center">
                                @include('clients.avatar')
                                <div class="ms-2 md:max-w-[35%] lg:max-w-[50%] xl:max-w-[60%]">
                                    <h2 class="mt-2 text-2xl font-bold text-gray-700 break-all text-justify">
                                        {{ $client->getFullName() }}
                                    </h2>
                                    <div class="-mt-1 text-justify text-gray-600 break-all text-justify">
                                        {{ $client->email }}
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 my-4 md:mb-0 rounded bg-gray-300 font-bold text-gray-800
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
                            <div class="flex flex-col sm:w-full sm:flex-row
                                        sm:justify-end md:absolute top-4 right-4">
                                <a href="{{ route('clients.show', $client->id) }}"
                                    class="px-4 py-2 mt-2 text-center shadow text-white font-bold
                                        bg-emerald-700 hover:bg-emerald-900 rounded
                                        transition ease-in-out duration-500 sm:me-2">
                                    Investimento
                                </a>
                                <a href="{{ route('clients.edit', $client->id) }}"
                                    class="px-4 py-2 mt-2 text-center shadow text-white font-bold
                                        bg-cyan-700 hover:bg-cyan-900 rounded
                                        transition ease-in-out duration-500 sm:me-2">
                                    Editar
                                </a>
                                <form class="inline" method="POST"
                                    action="{{ route('clients.destroy', $client->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2 shadow font-bold mt-2 text-center
                                        rounded bg-gray-300 text-red-700 hover:bg-red-600 hover:text-white
                                        transition ease-in-out duration-500"
                                        onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
                                        Deletar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="p-4 font-bold bg-amber-500 text-white rounded">
                Nenhum cliente encontrado!
            </div>
            @endif
            <div class="mt-4">
                {{ $clients->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
