<x-layouts.app title="Detalles del Cliente">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ url('/admin/clientes') }}">Listado de Clientes</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Datos del cliente: {{ $cliente->apellidos . ' ' . $cliente->nombres }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <br>
    <flux:separator variant="subtle" />

    {{-- Card Principal --}}
    <div class="bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">

        <div class="p-6">
            <div
                class="flex flex-col md:flex-row items-center gap-6 mb-8 bg-slate-50 dark:bg-neutral-700/30 p-6 rounded-2xl">
                <div class="relative">
                    <div
                        class="h-32 w-32 rounded-full border-4 border-white dark:border-neutral-800 shadow-md overflow-hidden bg-slate-200 flex items-center justify-center">
                        @if ($cliente->foto_perfil)
                            <img src="{{ asset('storage/' . $cliente->foto_perfil) }}" alt="Foto"
                                class="h-full w-full object-cover">
                        @else
                            <flux:icon name="user" class="text-slate-400 h-16 w-16" />
                        @endif
                    </div>

                </div>

                <div class="text-center md:text-left">
                    <flux:heading level="2" size="xl">{{ $cliente->nombres }} {{ $cliente->apellidos }}
                    </flux:heading>
                    <p class="text-blue-600 font-medium">{{ $cliente->user->roles->pluck('name')->implode(', ') ?? 'Sin Rol' }}</p>
                    <div class="mt-2 flex flex-wrap justify-center md:justify-start gap-3">
                        <span
                            class="px-3 py-1 bg-white dark:bg-neutral-800 rounded-full text-xs shadow-sm border border-slate-200 dark:border-neutral-600">
                            ID: #{{ $cliente->id }}
                        </span>
                        <span
                            class="px-3 py-1 bg-white dark:bg-neutral-800 rounded-full text-xs shadow-sm border border-slate-200 dark:border-neutral-600">
                            Desde: {{ $cliente->created_at->format('d/m/Y') }}
                        </span>
                        @if ($cliente->user->estado == 'Activo')
                            <span
                                class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400 border border-green-200 dark:border-green-500/20">
                                <span class="relative flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                {{ $cliente->user->estado }}
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400 border border-red-200 dark:border-red-500/20">
                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                {{ $cliente->user->estado }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- Columna Izquierda: Cuenta y Personal --}}
                <div class="space-y-6">
                    <section>
                        <flux:heading level="3" size="lg" class="mb-4 flex items-center gap-2">
                            <flux:icon name="identification" variant="micro" class="text-blue-500" />
                            Datos Personales
                        </flux:heading>
                        <div class="grid grid-cols-2 gap-4 bg-slate-50/50 dark:bg-neutral-900/20 p-4 rounded-xl">
                            <div>
                                <label class="text-xs text-slate-400 uppercase font-bold">Tipo Doc.</label>
                                <p class="text-sm text-slate-700 dark:text-neutral-200">{{ $cliente->tipo_documento }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 uppercase font-bold">Nro. Documento</label>
                                <p class="text-sm text-slate-700 dark:text-neutral-200">
                                    {{ $cliente->numero_documento }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 uppercase font-bold">Género</label>
                                <p class="text-sm text-slate-700 dark:text-neutral-200">{{ $cliente->genero }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 uppercase font-bold">Fecha Nacimiento</label>
                                <p class="text-sm text-slate-700 dark:text-neutral-200">
                                    {{ $cliente->fecha_nacimiento }}
                                </p>
                            </div>
                        </div>
                    </section>

                    <section>
                        <flux:heading level="3" size="lg" class="mb-4 flex items-center gap-2">
                            <flux:icon name="map-pin" variant="micro" class="text-blue-500" />
                            Ubicación y Contacto
                        </flux:heading>
                        <div class="space-y-3 bg-slate-50/50 dark:bg-neutral-900/20 p-4 rounded-xl">
                            <div>
                                <label class="text-xs text-slate-400 uppercase font-bold">Dirección</label>
                                <p class="text-sm text-slate-700 dark:text-neutral-200">{{ $cliente->direccion }}</p>
                            </div>
                            <div class="flex gap-8">
                                <div>
                                    <label class="text-xs text-slate-400 uppercase font-bold">Celular</label>
                                    <p class="text-sm text-slate-700 dark:text-neutral-200">{{ $cliente->celular }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-slate-400 uppercase font-bold">Email</label>
                                    <p class="text-sm text-slate-700 dark:text-neutral-200">{{ $cliente->user->email }}</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                {{-- Columna Derecha: Emergencia y Cuenta --}}
                <div class="space-y-6">
                    <section>
                        <flux:heading level="3" size="lg" class="mb-4 flex items-center gap-2 text-red-600">
                            <flux:icon name="phone-arrow-up-right" variant="micro" />
                            Contacto de Emergencia
                        </flux:heading>
                        <div class="space-y-3 border-l-4 border-red-100 dark:border-red-900/30 pl-4 py-2">
                            <div>
                                <label class="text-xs text-slate-400 uppercase font-bold">Nombre</label>
                                <p class="text-sm text-slate-700 dark:text-neutral-200 font-medium">
                                    {{ $cliente->contacto_nombre }}</p>
                            </div>
                            <div class="flex gap-8">
                                <div>
                                    <label class="text-xs text-slate-400 uppercase font-bold">Parentesco</label>
                                    <p class="text-sm text-slate-700 dark:text-neutral-200">
                                        {{ $cliente->contacto_relacion }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-slate-400 uppercase font-bold">Teléfono</label>
                                    <p class="text-sm text-slate-700 dark:text-neutral-200">
                                        {{ $cliente->contacto_telefono }}</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <flux:heading level="3" size="lg" class="mb-4 flex items-center gap-2">
                            <flux:icon name="shield-check" variant="micro" class="text-blue-500" />
                            Seguridad del Sistema
                        </flux:heading>
                        <div
                            class="bg-blue-50/30 dark:bg-blue-900/10 p-4 rounded-xl border border-blue-100 dark:border-blue-900/30">
                            <label class="text-xs text-slate-400 uppercase font-bold">Nombre de acceso (User)</label>
                            <p class="text-sm text-blue-700 dark:text-blue-300 font-mono">{{ $cliente->user->name }}</p>

                            <div class="mt-4 p-3 bg-white dark:bg-neutral-800 rounded-lg text-xs text-slate-500">
                                <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                                Última actualización: {{ $cliente->user->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    </section>
                </div>

            </div>
        </div>

        {{-- Footer con Botones --}}
        <div class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6">
            <div class="flex flex-wrap gap-3 justify-between">
                <a href="{{ url('/admin/clientes') }}"
                    class="px-5 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al listado
                </a>


            </div>
        </div>
    </div>
</x-layouts.app>
