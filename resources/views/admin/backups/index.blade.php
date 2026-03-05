<x-layouts.app title="Backups del Sistema">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Backups</flux:heading>
        <p class="text-slate-500 dark:text-neutral-400">Gestion copias de seguridad almacenadas en private.</p>
        <flux:separator variant="subtle" />
    </div>

    <div class="mb-4">
        <form action="{{ route('admin.backups.store') }}" method="POST">
            @csrf
            <flux:button variant="primary" type="submit" icon="archive-box-arrow-down">
                Crear Backup
            </flux:button>
        </form>
    </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-zinc-800 mt-6">
            <table class="min-w-full border-collapse text-sm">
                <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                    <tr>
                        <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Archivo
                        </th>
                        <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Tamaño
                        </th>
                        <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800">
                    @forelse ($backups as $backup)
                        <tr class="event:bg-slate-50 odd:bg-white dark:even:bg-zinc700/20 dark:odd:bg-zinc-800 hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                            <td
                                class="px-4 py-3 border  border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-gray-100">
                                {{ $backup['name'] }}
                            </td>
                            <td
                                class="px-4 py-3 border  border-gray-200 dark:border-zinc-700 text-center text-gray-900 dark:text-gray-100">
                                {{ number_format($backup['size'] / 1024 / 1024, 2) }} MB
                            </td>
                            <td
                                class="px-4 py-3 border  border-gray-200 dark:border-zinc-700 text-center text-gray-900 dark:text-gray-100">
                                {{ \Carbon\Carbon::createFromTimestamp($backup['last_modified'])->format('d/m/Y H:i:s') }}
                            </td>
                           <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.backups.download', ['file' => $backup['name']]) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded transition">
                                        Descargar
                                    </a>

                                    <form action="{{ route('admin.backups.destroy', ['file' => $backup['name']]) }}"
                                        method="POST" onsubmit="return confirm('¿Eliminar?');">
                                        @csrf

                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-8 text-center text-gray-500" colspan="4">
                                No hay Backups Disponibles.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
</x-layouts.app>
