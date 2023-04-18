<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Investimentos') }}
            </h2>
            <a href="{{route('investiments.create')}}"
            class="px-4 py-2 shadow text-white font-bold text-center
                   bg-green-700 hover:bg-green-900 rounded
                   transition ease-in-out duration-500 ml-5">
                Cadastrar Investimento
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 xl:px-8 sm:max-w-full">
            @if(session('msg'))
            <div class="bg-green-700 text-white p-4 rounded font-bold mb-10 mx-6 sm:mx-0">
                {{ session('msg') }}
            </div>
            @endif
            @if ($investiments->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl p-4 rounded
                            grid md:grid-cols-2">
                    @foreach ($investiments as $investiment)
                        <div class="bg-white rounded pb-44 md:pb-20 pt-4 px-4 m-4 relative">
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-700">
                                {{ $investiment->getAbbreviationUpper() }}
                            </h2>
                            <p class="border-b-2 text-gray-600 mb-4">
                                Nome Comercial: {{ $investiment->name }}
                            </p>
                            <p class="mt-8 text-justify">
                                Descrição: {{ $investiment->description }}
                            </p>
                            <div class="flex flex-col md:w-28 md:flex-row px-4
                                         absolute bottom-5 left-0 w-full">
                                <a href="{{ route('investiments.show', $investiment->id) }}"
                                    class="px-4 py-2 mt-2 text-center shadow text-white font-bold
                                        bg-emerald-700 hover:bg-emerald-900 rounded
                                        transition ease-in-out duration-500 md:me-2">
                                    Visualizar
                                </a>
                                <a href="{{ route('investiments.edit', $investiment->id) }}"
                                    class="px-4 py-2 mt-2 text-center shadow text-white font-bold
                                        bg-cyan-700 hover:bg-cyan-900 rounded
                                        transition ease-in-out duration-500 md:me-2">
                                    Editar
                                </a>
                                <form class="inline" method="POST"
                                    action="{{ route('investiments.destroy', $investiment->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2 shadow font-bold mt-2 text-center
                                        rounded bg-gray-300 text-red-700 hover:bg-red-600 hover:text-white
                                        transition ease-in-out duration-500"
                                        onclick="return confirm('Tem certeza que deseja excluir este investimento? Todos os valores aplicados serão devolvidos.')">
                                        Encerrar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-4 font-bold bg-amber-500 text-white font-bold rounded">
                    Nenhum investimento encontrado!
                </div>
            @endif
            <div class="mt-4">
                {{ $investiments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
