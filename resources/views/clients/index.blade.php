<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gerenciamento de Clientes') }}
            </h2>
            <a href="{{route('clients.create')}}"
            class="px-4 py-2 shadow text-white font-bold
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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full bg-white rounded shadow p-4">
                    <thead>
                        <tr>
                            <th class="px-2 py-4 text-left"></th>
                            <th class="px-2 py-4 text-left">Nome completo</th>
                            <th class="px-2 py-4 text-left">E-mail</th>
                            <th class="px-2 py-4 text-left">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clients as $client)
                        <tr>
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
                            <td class="px-2 py-4">{{ $client->getFullName() }}</td>
                            <td class="px-2 py-4 break-all">{{ $client->email }}</td>
                            <td class="px-2 py-4 flex flex-col sm:flex-row">
                                <form class="inline" method="POST"
                                    action="{{ route('clients.destroy', $client->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este cliente?')"
                                            class="w-full sm:w-auto px-4 py-1.5 me-2 shadow text-red-700 font-bold
                                                rounded hover:bg-red-600 hover:text-white transition
                                                ease-in-out duration-800">
                                        Deletar
                                    </button>
                                </form>
                                <a href="{{ route('clients.edit', $client->id) }}"
                                    class="w-full sm:w-auto px-4 py-2 me-2 shadow text-white font-bold
                                           bg-blue-700 hover:bg-blue-900 rounded
                                           transition ease-in-out duration-500">
                                     Editar
                                 </a>
                                <a href="{{ route('clients.show', $client->id) }}"
                                   class="w-full sm:w-auto px-4 py-2 shadow text-white font-bold
                                          bg-emerald-700 hover:bg-emerald-900 rounded
                                          transition ease-in-out duration-500">
                                    Investimento
                                </a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="4">Nenhum cliente encontrado!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $clients->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
