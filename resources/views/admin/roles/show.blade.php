<x-layouts.app title="Roles del Sistema">

    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ url('/admin/roles') }}">Listado de Roles</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Datos del Rol: {{ $rol->name }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <br>
    <flux:separator variant="subtle" />
    <br>

    {{-- Card --}}
    <div
        class="max-w-xl bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg transition-all duration-300 hover:shadow-xl">


            {{-- Body --}}
            <div class="p-6">
                {{-- Formulario en grid responsivo: 1 columna en movil, 2 en md+ --}}
                <div class="grid grid-cols-1">
                        <div class="mb-4">
                            <flux:label>Nombre del Rol </flux:label>
                            <p><i class="fas fa-shield-alt"></i> {{ $rol->name }}</p>
                        </div>

                        <div class="mb-4">
                            <flux:label>Fecha y Hora de Registro </flux:label>
                            <p><i class="fas fa-clock"></i> {{ $rol->created_at }}</p>
                        </div>


                </div>
            </div>

            {{-- Footer --}}
            <div
                class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6 text-left">
                <div class="flex space-x-3">
                    <a href="{{ url('/admin/roles') }}"
                        class="px-5 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none
                        focus:ring-2 focus:ring-gray-200 focus:ring-offset-1 transition-all duration-200 inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al Listado
                    </a>


                </div>
            </div>
    </div>
    {{-- End Card --}}


</x-layouts.app>
