<x-layouts.app title="Crear Rol del Sistema">

    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ url('/admin/roles') }}">Listado de Roles</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Creacion de un Nuevo Rol</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <br>
    <flux:separator variant="subtle" />
    <br>

    {{-- Card --}}
    <div
        class="max-w-xl bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg transition-all duration-300 hover:shadow-xl">

        <form action="{{ url('/admin/roles/create') }}" method="POST">
            @csrf
            {{-- Body --}}
            <div class="p-6">
                {{-- Formulario en grid responsivo: 1 columna en movil, 2 en md+ --}}
                <div class="grid grid-cols-1">
                        <div class="mb-4">
                            <flux:label>Nombre del Rol <span class="text-red-500" title="Campo obligatorio">
                                (*)</span></flux:label>
                            <flux:input name="name" icon="shield-check" value="{{ old('name') }}"
                                 placeholder="Nombre del Rol..." required />
                            <flux:error name="name" />
                        </div>


                </div>
            </div>

            {{-- Footer --}}
            <div
                class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6 text-left">
                <div class="flex space-x-3">
                    <a href="{{ url('/admin/roles') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none
                        focus:ring-2 focus:ring-gray-200 focus:ring-offset-1 transition-all duration-200 inline-flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <flux:button variant="primary" type="submit" class="cursor-pinter" color="blue">
                        <i class="fas fa-save"></i> Guardar
                    </flux:button>

                </div>
            </div>

        </form>
    </div>
    {{-- End Card --}}


</x-layouts.app>
