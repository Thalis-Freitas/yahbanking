<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cadastrar Investimento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-5">
            @if($errors->any())
            <div class="bg-red-700 text-white p-4 rounded font-bold mb-10">
                Não foi possível realizar o cadastro.
            </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">
                <form action="{{route('investiments.store')}}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="w-full mb-6">
                        <label for="" class="block text-white mb-2">Sigla</label>
                        <input type="text" class="w-full rounded" name="abbreviation">
                        @error('abbreviation')
                            <span class="text-red-600"> {{ $message }} </span>
                        @enderror
                    </div>
                    <div class="w-full mb-6">
                        <label for="" class="block text-white mb-2">Nome comercial</label>
                        <input type="text" class="w-full rounded" name="name">
                        @error('name')
                            <span class="text-red-600"> {{ $message }} </span>
                        @enderror
                    </div>
                    <div class="w-full mb-6">
                        <label for="" class="block text-white mb-2">Descrição</label>
                        <input type="text" class="w-full rounded" name="description">
                        @error('description')
                            <span class="text-red-600"> {{ $message }} </span>
                        @enderror
                    </div>
                    <button class="px-4 py-2 shadow text-white font-bold
                                    bg-green-700 hover:bg-green-900 rounded
                                    transition ease-in-out duration-500">
                        Cadastrar Investimento
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
