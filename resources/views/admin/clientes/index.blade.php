<x-layouts.app title="Clientes del Sistema">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Listado de Clientes</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex justify-end">
        <a href="{{ url('/admin/clientes/create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded transition">
            <i class="fas fa-plus mr-2"></i>
            Crear Nuevo
        </a>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nro</th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nombres y Apellidos
                    </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Email
                    </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Documento
                    </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Celular
                    </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Estado
                    </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @foreach ($clientes as $cliente)
                    <tr
                        class="even:bg-slate-50 odd:bg-white dark:even:bg-zinc-700/20 dark:odd:bg-zinc-800 hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ $loop->iteration }}</td>
                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $cliente->apellidos . ' ' . $cliente->nombres }}</td>
                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $cliente->user->email }}
                        </td>
                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $cliente->tipo_documento . ' ' . $cliente->numero_documento }}</td>
                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $cliente->celular }}
                        </td>

                        <td
                            class="text-center px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            @if($cliente->user->estado == 'Activo')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Activo
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactivo
                                </span>
                            @endif
                        </td>

                        <td class=" px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ url('/admin/cliente/' . $cliente->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded transition">
                                        <i class="fas fa-eye mr-2"></i> Ver
                                    </a>
                                    <a href="{{ url('/admin/cliente/' . $cliente->id . '/edit') }}"
                                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition">
                                        <i class="fas fa-pencil-alt mr-2"></i> Editar
                                    </a>

                                    <form action="{{ url('/admin/cliente/' . $cliente->id) }}" method="post"
                                        id="miFormulario{{ $cliente->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition"
                                            onclick="preguntar{{ $cliente->id }}(event)">
                                            <i class="fas fa-trash-alt mr-2"></i> Eliminar
                                        </button>
                                    </form>

                                    <script>
                                        function preguntar{{ $cliente->id }}(event) {
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
                                                    document.getElementById('miFormulario{{ $cliente->id }}').submit();
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

    @if($clientes->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando del
                <span class="font-semibold">{{ $clientes->firstItem() }}</span>
                al
                <span class="font-semibold">{{ $clientes->lastItem() }}</span>
                de
                <span class="font-semibold">{{ $clientes->total() }}</span>
                resultados.
            </div>
            <div>
                {{ $clientes->links() }}
            </div>
        </div>
    @endif


</x-layouts.app>
