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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('msg'))
            <div class="bg-green-700 text-white p-4 rounded font-bold mb-10">
                {{ session('msg') }}
            </div>
            @endif
            @if ($clients->isNotEmpty())
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4 rounded">
                    @foreach ($clients as $client)
                        <div class="bg-white rounded p-4 m-4">
                            <div class="flex">
                                @if ($client->avatar)
                                    <img class="rounded-full w-16 h-16" alt="{{ $client->name . 'avatar' }}"
                                        src="{{ $client->getAvatarUrl() }}">
                                @else
                                    <img class="w-16 h-16" alt="{{ $client->name . 'avatar' }}"
                                    src="/img/avatardefault.svg">
                                @endif
                                <div class="ms-2">
                                    <h2 class="mt-2 text-2xl font-bold text-gray-700 break-word text-justify">
                                        {{ $client->getFullName() }}
                                    </h2>
                                    <div class="-mt-1 text-justify text-gray-600 break-all text-justify">
                                        {{ $client->email }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col sm:relative sm:w-28 sm:flex-row
                                        sm:justify-items-start">
                                <a href="{{ route('clients.show', $client->id) }}"
                                    class="px-4 py-2 mt-2 text-center shadow text-white font-bold
                                        bg-emerald-700 hover:bg-emerald-900 rounded
                                        transition ease-in-out duration-500 sm:me-2">
                                    Investimento
                                </a>
                                <a href="{{ route('clients.edit', $client->id) }}"
                                    class="px-4 py-2 mt-2 text-center shadow text-white font-bold
                                        bg-blue-700 hover:bg-blue-900 rounded
                                        transition ease-in-out duration-500 sm:me-2">
                                    Editar
                                </a>
                                <form class="inline" method="POST"
                                    action="{{ route('clients.destroy', $client->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2 shadow font-bold mt-2 text-center
                                        rounded bg-red-600 hover:bg-red-800 text-white transition ease-in-out duration-500"
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
            <div class="p-4 text-bold bg-amber-500 text-white font-bold rounded">
                Nenhum cliente encontrado!
            </div>
            @endif
            <div class="mt-4">
                {{ $clients->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
