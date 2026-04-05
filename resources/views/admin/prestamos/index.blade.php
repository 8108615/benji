<x-layouts.app title="Prestamos del sistema">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Listado de prestamos</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ url('/admin/prestamos') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar prestamos..."
                        value="{{ $_REQUEST['buscar'] ?? '' }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg
                transition flex items-center gap-2">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
                @if (isset($_REQUEST['buscar']))
                    <a href="{{ url('/admin/prestamos') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold
                    rounded-lg transition
                    flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>
        <div class="flex-1 justify-end flex">
            <a href="{{ url('/admin/prestamos/create') }}"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus mr-2"></i>
                Crear nuevo
            </a>
        </div>
    </div>

    @if (request('buscar'))
        <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
            <p class="text-sm text-gray-700 dark:text-black-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $prestamos->total() == 1 ? 'encontró' : 'encontraron' }}
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $prestamos->total() }}</span>
                {{ $prestamos->total() == 1 ? 'resultado' : 'resultados' }}
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
                        Usuario que Realizó el Préstamo</th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Cliente</th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Documento </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Categoria </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Monto Préstado </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Tasa de Interés </th>

                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Modalidad de pago </th>

                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nro de cuotas </th>

                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Cuotas Pagadas</th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Cuotas Pendientes </th>
                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Estado </th>


                    <th
                        class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @php
                    $nro = ($prestamos->currentPage() - 1) * $prestamos->perPage() + 1;
                @endphp
                @foreach ($prestamos as $prestamo)
                    @php
                        $cuotasPagadas = $prestamo->pagos->where('estado','pagado')->count();
                    @endphp
                    <tr
                        class="even:bg-slate-50 odd:bg-white dark:even:bg-zinc-700/20 dark:odd:bg-zinc-800 hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ $nro++ }}</td>

                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ $prestamo->usuario->name }} <br>
                            <small>{{ $prestamo->usuario->roles->pluck('name')->join(', ') }}</small>
                        </td>
                        <td
                            class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ $prestamo->cliente->apellidos . ' ' . $prestamo->cliente->nombres }}</td>


                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            {{ $prestamo->cliente->tipo_documento . ' ' . $prestamo->cliente->numero_documento }}
                        </td>

                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            {{ $prestamo->categoria->nombre }}
                        </td>

                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            {{ $ajuste->divisa }} {{ number_format($prestamo->monto_prestado, 2) }}
                        </td>

                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            {{ $prestamo->tasa_interes }}%
                        </td>

                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            {{ $prestamo->modalidad_pago }}
                        </td>

                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            {{ $prestamo->nro_cuotas }}
                        </td>

                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            {{ $cuotasPagadas }}
                        </td>

                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            @php
                                echo $cuotasPendientes = $prestamo->pagos->where('estado', 'pendiente')->count();
                            @endphp
                        </td>

                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            @if($prestamo->estado == 'pendiente')
                                <span
                                    class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-medium bg-yellow-200 text-yellow-700
                                    dark:bg-yellow-500/10 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-500/20">
                                <span class="h-2 w-2 rounded-full bg-yellow-500"></span>
                                    {{ $prestamo->estado }}
                            </span>
                            @elseif($prestamo->estado == 'pagado')
                                <span
                                    class="inline-flex items-center gap-1.5 py-1 px-5 rounded-full text-xs font-medium bg-green-200 text-green-700
                                    dark:bg-green-500/10 dark:text-green-400 border border-green-200 dark:border-green-500/20">
                                    <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                        {{ $prestamo->estado }}
                            </span>
                            @endif
                        </td>

                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">

                            <div class="flex justify-center gap-2">

                                <a href="{{ url('/admin/prestamo/' . $prestamo->id . '/contrato') }}" target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded transition">
                                    <i class="fas fa-print mr-2"></i> Contrato
                                </a>

                                <a href="{{ url('/admin/prestamo/' . $prestamo->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded transition">
                                    <i class="fas fa-eye mr-2"></i> Ver Pagos
                                </a>

                                @if(!$cuotasPagadas > 0)
                                    <a href="{{ url('/admin/prestamo/' . $prestamo->id . '/edit') }}"
                                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition">
                                        <i class="fas fa-pencil-alt mr-2"></i> Editar
                                    </a>

                                    <form action="{{ url('/admin/prestamo/' . $prestamo->id) }}" method="post"
                                        id="miFormulario{{ $prestamo->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition"
                                            onclick="preguntar{{ $prestamo->id }}(event)">
                                            <i class="fas fa-trash-alt mr-2"></i> Eliminar
                                        </button>
                                    </form>
                                @endif



                                <script>
                                    function preguntar{{ $prestamo->id }}(event) {
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
                                                document.getElementById('miFormulario{{ $prestamo->id }}').submit();
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
        /* Ocultar textos en inglés de la paginación */
        nav[role="navigation"] p {
            display: none !important;
        }
    </style>

    @if ($prestamos->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando
                <span class="font-semibold">{{ $prestamos->firstItem() }}</span>
                al
                <span class="font-semibold">{{ $prestamos->lastItem() }}</span>
                de
                <span class="font-semibold">{{ $prestamos->total() }}</span>
                resultados.
            </div>
            <div>
                {{ $prestamos->links() }}
            </div>
        </div>
    @endif



</x-layouts.app>
