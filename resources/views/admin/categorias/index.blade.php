<x-layouts.app title="Categorías del sistema">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Listado de categorías</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ url('/admin/categorias') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar usuarios..."
                        value="{{ $_REQUEST['buscar'] ?? '' }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg
                transition flex items-center gap-2">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
                @if (isset($_REQUEST['buscar']))
                    <a href="{{ url('/admin/categorias') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold
                    rounded-lg transition
                    flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>
        <div class="flex-1 justify-end flex">

            <flux:modal.trigger name="create-categoria" variant="primary" data-open-modal>
                <flux:button variant="primary" icon="plus">Crear nueva categoría</flux:button>
            </flux:modal.trigger>

            <flux:modal name="create-categoria" variant="primary" class="md:w-96">
                <form action="{{ url('/admin/categorias/create') }}" method="post">
                    @csrf
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                    <i class="fas fa-tag text-blue-600 dark:text-blue-400 text-lg"></i>
                                </div>
                                <div>
                                    <flux:heading size="lg">Nueva categoría</flux:heading>
                                    <flux:text class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Agrega una nueva categoría de préstamo
                                    </flux:text>
                                </div>
                            </div>
                        </div>

                        <label for="">Nombre</label>
                        <flux:input placeholder="Ej: Prestamos personales" name="nombre" icon="tag"
                            value="{{ old('nombre') }}" required />
                        <flux:error name="nombre" />

                        <label for="">Porcentaje</label>
                        <flux:input placeholder="Ej: 10.00" name="porcentaje" value="{{ old('porcentaje') }}"
                            required />
                        <flux:error name="porcentaje" />

                        <div class="flex">
                            <flux:spacer />

                            <flux:button type="submit" variant="primary"><i class="fas fa-save mr-2"></i> Crear
                                categoría</flux:button>
                        </div>
                    </div>
                </form>
            </flux:modal>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalId = @json(session('modal_id'));
                const selector = modalId ?
                    `[data-open-modal="${modalId}"] button` :
                    '[data-open-modal] button';
                const button = document.querySelector(selector);
                if (button) {
                    setTimeout(() => button.click(), 100);
                }
            });
        </script>
    @endif

    @if (request('buscar'))
        <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
            <p class="text-sm text-gray-700 dark:text-black-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $categorias->total() == 1 ? 'encontró' : 'encontraron' }}
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
                        Porcentaje</th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @php
                    $nro = ($categorias->currentPage() - 1) * $categorias->perPage() + 1;
                @endphp
                @foreach ($categorias as $categoria)
                    <tr
                        class="even:bg-slate-50 odd:bg-white dark:even:bg-zinc-700/20 dark:odd:bg-zinc-800 hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ $nro++ }}</td>

                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $categoria->nombre }} </td>

                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $categoria->porcentaje }}%</td>

                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap">
                            <div class="flex justify-center gap-2">

                                <flux:button.group>

                                    <flux:modal.trigger name="show-categoria{{ $categoria->id }}" variant="primary"
                                        data-open-modal>
                                        <flux:button variant="primary" class="cursor-pointer" color="cyan"
                                            icon="eye">
                                            Ver</flux:button>
                                    </flux:modal.trigger>

                                    <flux:modal name="show-categoria{{ $categoria->id }}" variant="primary"
                                        class="md:w-96">
                                        <div class="space-y-6">
                                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                                        <i
                                                            class="fas fa-tag text-blue-600 dark:text-blue-400 text-lg"></i>
                                                    </div>
                                                    <div>
                                                        <flux:heading size="lg">Categoría registrada</flux:heading>
                                                        <flux:text
                                                            class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                            Datos de la categoría de préstamo
                                                        </flux:text>
                                                    </div>
                                                </div>
                                            </div>
                                            <label for="">Nombre</label>
                                            <p><i class="fas fa-tag"></i> {{ $categoria->nombre }}</p>
                                            <label for="">Porcentaje</label>
                                            <p><i class="fas fa-percent"></i> {{ $categoria->porcentaje }}%</p>
                                        </div>
                                    </flux:modal>


                                    <flux:modal.trigger name="edit-categoria{{ $categoria->id }}" variant="primary"
                                        data-open-modal="{{ $categoria->id }}">
                                        <flux:button variant="primary" class="cursor-pointer" color="green"
                                            icon="pencil">
                                            Editar
                                        </flux:button>
                                    </flux:modal.trigger>

                                    <flux:modal name="edit-categoria{{ $categoria->id }}" variant="primary"
                                        class="md:w-96">
                                        <form action="{{ url('/admin/categoria/' . $categoria->id) }}"
                                            method="post">
                                            @csrf
                                            @method('PUT')
                                            <div class="space-y-6">
                                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                                    <div class="flex items-center gap-3 mb-2">
                                                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                                            <i
                                                                class="fas fa-tag text-blue-600 dark:text-blue-400 text-lg"></i>
                                                        </div>
                                                        <div>
                                                            <flux:heading size="lg">Editar categoría
                                                            </flux:heading>
                                                            <flux:text
                                                                class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                                Modifica los datos de la categoría de préstamo
                                                            </flux:text>
                                                        </div>
                                                    </div>
                                                </div>

                                                <label for="">Nombre</label>
                                                <flux:input placeholder="Ej: Prestamos personales" name="nombre"
                                                    icon="tag" value="{{ old('nombre', $categoria->nombre) }}"
                                                    required />
                                                <flux:error name="nombre" />

                                                <label for="">Porcentaje</label>
                                                <flux:input placeholder="Ej: 10.00" name="porcentaje"
                                                    value="{{ old('porcentaje', $categoria->porcentaje) }}"
                                                    required />
                                                <flux:error name="porcentaje" />

                                                <div class="flex">
                                                    <flux:spacer />

                                                    <flux:button type="submit" variant="primary"><i
                                                            class="fas fa-save mr-2"></i> Actualizar
                                                        categoría</flux:button>
                                                </div>
                                            </div>
                                        </form>
                                    </flux:modal>

                                    <flux:modal.trigger name="delete-categoria{{ $categoria->id }}" variant="danger">
                                        <flux:button variant="danger" class="cursor-pointer"
                                            style="border-radius: 0px 7px 7px 0px"><i class="fas fa-trash-alt"></i>
                                            Borrar
                                        </flux:button>
                                    </flux:modal.trigger>

                                    <flux:modal name="delete-categoria{{ $categoria->id }}" class="min-w-[22rem]">
                                        <form action="{{ url('/admin/categoria/' . $categoria->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="space-y-6">
                                                <div>
                                                    <flux:heading size="lg">Borrar categoría</flux:heading>

                                                    @if ($categoria->prestamos_count > 0)
                                                        <flux:text class="mt-2 text-red-500">
                                                            Esta categoría tiene {{ $categoria->prestamos_count }}
                                                            préstamos asociados. <br>
                                                            No se puede borrar el Préstamo.
                                                        </flux:text>
                                                    @else
                                                        <flux:text class="mt-2">
                                                            Estás a punto de borrar esta categoría.<br>
                                                            Esta acción no se puede deshacer.
                                                        </flux:text>
                                                    @endif

                                                </div>

                                                <div class="flex gap-2">
                                                    <flux:spacer />
                                                    @if ($categoria->prestamos_count > 0)
                                                        <flux:modal.close>
                                                            <flux:button variant="danger">Aceptar</flux:button>
                                                        </flux:modal.close>
                                                    @else
                                                        <flux:modal.close>
                                                            <flux:button variant="ghost">Cancelar</flux:button>
                                                        </flux:modal.close>

                                                        <flux:button type="submit" variant="danger">Borrar categoría
                                                        </flux:button>
                                                    @endif


                                                </div>
                                            </div>
                                        </form>
                                    </flux:modal>

                                </flux:button.group>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        /* Ocultar textos en inglés de la paginación */
        nav[role="navigation"] p {
            display: none !important;
        }
    </style>

    @if ($categorias->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando
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
