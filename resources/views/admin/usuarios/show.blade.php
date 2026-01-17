<x-layouts.app title="Detalles del Sistema">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ url('/admin/usuarios') }}">Listado de Usuarios</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Datos del Usuario: {{ $usuario->name }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <br>
    <flux:separator variant="subtle" />
    <br>

    {{-- Card --}}
    <div
        class="max-w-xl bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg">

            {{-- Body --}}
            <div class="p-6">
                {{-- Formulario en grid responsivo: 1 columna en movil, 2 en md+ --}}
                <div
                    class="flex flex-col md:flex-row items-center gap-6 mb-8 bg-slate-50 dark:bg-neutral-700/30 p-6 rounded-2xl">
                    <div class="relative">
                        <div
                            class="h-32 w-32 rounded-full border-4 border-white dark:border-neutral-800 shadow-md overflow-hidden bg-slate-200 flex items-center justify-center">
                            @if ($usuario->foto_perfil)
                                <img src="{{ asset('storage/' . $usuario->foto_perfil) }}" alt="Foto"
                                    class="h-full w-full object-cover">
                            @else
                                <flux:icon name="user" class="text-slate-400 h-16 w-16" />
                            @endif
                        </div>
                        <div class="absolute bottom-1 right-1">
                            @if ($usuario->estado == 'Activo')
                                <span class="flex h-5 w-5 rounded-full bg-green-500 border-2 border-white"
                                    title="Activo"></span>
                            @else
                                <span class="flex h-5 w-5 rounded-full bg-red-500 border-2 border-white"
                                    title="Inactivo"></span>
                            @endif
                        </div>
                    </div>

                    <div class="text-center md:text-left">
                        <flux:heading level="2" size="x1">{{ $usuario->nombres }} {{ $usuario->apellidos }}
                        </flux:heading>
                        <p class="text-blue-600 font-medium">{{ $usuario->getRoleNames()->first() ?? 'Sin Rol' }}</p>
                        <div class="mt-2 flex flex-wrap justify-center md: justify-start gap-3">
                            <span
                                class="px-3 py-1 bg-white dark:bg-neutral-800 rounded-full text-xs shadow-sm border border-slate-200 dark:border-neutral-600">
                                    ID: #{{ $usuario->id }}
                            </span>
                            <span
                                class="px-3 py-1 bg-white dark:bg-neutral-800 rounded-full text-xs shadow-sm border border-slate-200 dark:border-neutral-600">
                                Desde: {{ $usuario->created_at->format('d/m/Y')}}
                            </span>
                        </div>
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
