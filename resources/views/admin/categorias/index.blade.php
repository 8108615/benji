<x-layouts.app title="Categorias del Sistema">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Listado de Categorias</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ url('/admin/categorias') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar Categoria..."
                        value="{{ $_REQUEST['buscar'] ?? '' }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
                @if (isset($_REQUEST['buscar']))
                    <a href="{{ url('/admin/categorias') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg
                         transition flex items-center gap-2">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <div class="flex-1 justify-end flex">
            <flux:modal.trigger name="create-categoria" variant="primary">
                <flux:button variant="primary" icon="plus">Crear Nueva Categoría</flux:button>
            </flux:modal.trigger>

            <flux:modal name="create-categoria" variant="primary" class="md:w-96">
                <form action="{{ url('/admin/categorias/create') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                    <i class="fas fa-tag text-blue-600 dark:text-blue-400 text-lg"></i>
                                </div>
                                 <div>
                                    <flux:heading size="lg">Nueva Categoría</flux:heading>
                                    <flux:text class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Agregar una Nueva Categoria de Prestamo
                                    </flux:text>
                                </div>
                            </div>
                        </div>

                        <label for="">Nombre</label>
                        <flux:input placeholder="Ej: Prestamo Personales" name="nombre" icon="tag" required />

                        <div class="flex">
                            <flux:spacer />
                            <flux:button type="submit" variant="primary"><i class="fas fa-save mr-2 "></i> Crear Categoría</flux:button>
                        </div>
                    </div>
                </form>
            </flux:modal>
        </div>
    </div>

    @if (request('buscar'))
        <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700 rounded-lg">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $categorias->total() == 1 ? 'encontro' : 'encontraron' }}
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $categorias->total() }}</span>
                {{ $categorias->total() == 1 ? 'resultado' : 'resultados' }}
                con la busqueda: <span class="font-semibold">"{{ request('buscar') }}"</span>
            </p>
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nro</th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nombre
                    </th>

                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @php
                    $nro = ($categorias->currentPage() - 1) * $categorias->perPage() + 1;
                @endphp
                @foreach ($categorias as $categoria)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ $nro++ }}</td>
                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $categoria->nombre }}</td>

                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">


                            <div class="flex justify-center gap-2">
                                <a href="{{ url('/admin/categoria/' . $categoria->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded transition">
                                    <i class="fas fa-eye mr-2"></i> Ver
                                </a>
                                <a href="{{ url('/admin/categoria/' . $categoria->id . '/edit') }}"
                                    class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition">
                                    <i class="fas fa-pencil-alt mr-2"></i> Editar
                                </a>

                                <form action="{{ url('/admin/categoria/' . $categoria->id) }}" method="post"
                                    id="miFormulario{{ $categoria->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition"
                                        onclick="preguntar{{ $categoria->id }}(event)">
                                        <i class="fas fa-trash-alt mr-2"></i> Eliminar
                                    </button>
                                </form>

                                <script>
                                    function preguntar{{ $categoria->id }}(event) {
                                        event.preventDefault();

                                        Swal.fire({
                                            title: '¿Desea eliminar este registro?',
                                            text: '',
                                            icon: 'question',
                                            showDenyButton: true,
                                            confirmButtonText: 'Eliminar',
                                            confirmButtonColor: '#a5161d',
                                            denyButtonColor: '#270a0a',
                                            denyButtonText: 'Cancelar',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // JavaScript puro para enviar el formulario
                                                document.getElementById('miFormulario{{ $categoria->id }}').submit();
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <style>
        /* Ocultar textos en ingles de la paginacion */
        nav[role="navigation"] p {
            display: none !important;
        }
    </style>

    @if ($categorias->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando del
                <span class="font-semibold">{{ $categorias->firstItem() }}</span>
                al
                <span class="font-semibold">{{ $categorias->lastItem() }}</span>
                de
                <span class="font-semibold">{{ $categorias->total() }}</span>
                resultados.
            </div>
            <div>
                {{ $categorias->links() }}
            </div>
        </div>
    @endif




</x-layouts.app>
