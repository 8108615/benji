<x-layouts.app title="Permisos del Rol: {{ $rol->name }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Permisos del Rol: {{ $rol->name }}</flux:heading>
        <p class="text-slate-500 dark:text-neutral-400">Gestion de permisos para el rol seleccionado.</p>
        <flux:separator variant="subtle" />
    </div>

    <form action="{{ url('/admin/rol/' . $rol->id . '/update_permisos') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-neutral-800">
            <div class="p-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($permisos as $modulo => $grupoPermisos)
                        <div>
                            <h4 class="mb-3 text-base font-semibold text-gray-900 dark:text-gray-100">
                                {{ $modulo }}</h4>
                            <div class="space-y-2">
                                @foreach ($grupoPermisos as $permiso)
                                    <label for="permiso_{{ $permiso->id }}" class="flex items-center gap-2">
                                        <input class="h-4 w-4 rounded border-gray-300 text-blue-600 focus-ring-blue-500"
                                            type="checkbox" name="permisos[]" value="{{ $permiso->id }}"
                                            id="permiso_{{ $permiso->id }}" @checked($rol->hasPermissionTo($permiso->name))>
                                        <span class="text-sm text-gray-700 dark:text-gray-300"> {{ $permiso->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="border-t border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-neutral-700">
                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="inline-flex items-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 ">
                        <i class="bi bi-save mr-2"></i> Guardar permisos
                    </button>
                    <a href="{{ url('/admin/roles') }}"
                        class="inline-flex items-center rounded-lg bg-gray-300 px-4 py-2 bg-white text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:bg-neutral-600 dark:text-gray-300 dark:hover:bg-neutral-500">
                        <i class="bi bi-arrow-left mr-2"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.app>
