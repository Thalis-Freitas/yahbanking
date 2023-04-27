<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cadastrar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-5">
            @include('components.error-alert', ['message' => 'Não foi possível realizar o cadastro.'])
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">
                <form action="{{route('clients.store')}}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @include('clients.form')
                    <button class="px-4 py-2 shadow text-white font-bold
                                    bg-green-700 hover:bg-green-900 rounded
                                    transition ease-in-out duration-500">
                        Cadastrar Cliente
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
