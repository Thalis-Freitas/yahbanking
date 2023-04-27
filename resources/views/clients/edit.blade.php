<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight break-word">
                Atualizar Informações de: {{ $client->getFullName() }}
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-5">
            @if($errors->any())
            <div class="bg-red-700 text-white p-4 rounded font-bold mb-10 mx-6 sm:mx-0">
               Não foi possível atualizar o cadastro.
            </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">
                <form action="{{ route('clients.update', $client->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method("PATCH")
                    @include('clients.form')
                    <button class="px-4 py-2 shadow text-white font-bold
                                    bg-green-700 hover:bg-green-900 rounded
                                    transition ease-in-out duration-500">
                        Atualizar Dados
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
