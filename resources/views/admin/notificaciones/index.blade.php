<x-layouts.app title="Notificaciones de cuotas vencidas">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Notificaciones</flux:heading>
        <p class="text-slate-500 dark:text-neutral-400">Listado de Clientes con Cuotas Vencidas</p>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ url('/admin/notificaciones') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar po clientes, documento o celular..."
                        value="{{ $buscar }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
                @if ($buscar)
                    <a href="{{ url('/admin/notificaciones') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th
                        class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Cliente
                    </th>
                    <th
                        class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Documento </th>
                    <th
                        class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Celular </th>
                    <th
                        class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Cuotas Vencidas </th>
                    <th
                        class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Monto Vencido
                    </th>

                    <th
                        class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Primer Vencimiento
                    </th>
                    <th
                        class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @forelse($clientes as $cliente)
                    <tr
                        class="even:bg-slate-50 odd:bg-white dark:even:bg-zinc-700/20 dark:odd:bg-zinc-800 hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                        <td
                            class="px-4 py-2 border border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-gray-100">
                            {{ $cliente->apellidos }} {{ $cliente->nombres }}
                        </td>
                        <td
                            class="px-4 py-2 border border-gray-200 dark:border-zinc-700 text-center text-gray-900 dark:text-gray-100 ">
                            {{ $cliente->tipo_documento }} {{ $cliente->numero_documento }}
                        </td>
                        <td
                            class="px-4 py-2 border border-gray-200 dark:border-zinc-700 text-center text-gray-900 dark:text-gray-100">
                            {{ $cliente->celular }}
                        </td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-zinc-700 text-center">
                            <span class="inline-flex items-center rounded-full bg-amber-100 text-amber-700 px-2.5 py-0.5 text-xs font-semibold">
                                {{ $cliente->cuotas_vencidas_total }}
                            </span>
                        </td>
                        <td
                            class="px-4 py-2 border border-gray-200 dark:border-zinc-700 text-center text-gray-900 dark:text-gray-100 font-semibold">
                            {{ $ajuste->divisa ?? '$'}} {{ number_format($cliente->monto_vencido_total ?? 0, 2) }}
                        </td>
                        <td
                            class="px-4 py-2 border border-gray-200 dark:border-zinc-700 text-center text-gray-900 dark:text-gray-100">
                            {{ $cliente->primer_vencimiento ? \Carbon\Carbon::parse($cliente->primer_vencimiento)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-zinc-700 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <form action="{{ route('admin.notificaciones.notificarEmail', $cliente) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded transition">
                                        <i class="fas fa-envelope mr-2"></i> Notificar por email
                                    </button>
                                </form>
                                <a href="{{ route('admin.notificaciones.notificarWhatsapp', $cliente) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded transition">
                                    <i class="fab fa-whatsapp mr-2"></i> Notificar por WhatsApp
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-8 text-center text-gray-500" colspan="7">
                            No hay Clientes con Cuotas Vencidas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
