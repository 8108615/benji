<x-layouts.app title="Editar Usuario: {{ $usuario->nombres }}">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ url('/admin/usuarios') }}">Listado de Usuarios</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Modificar datos del usuario: {{ $usuario->name }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <br>
    <flux:separator variant="subtle" />

    {{-- Card Principal --}}
    <div class="bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">

        {{-- IMPORTANTE: Cambiamos la URL y agregamos @method('PUT') --}}
        <form action="{{ url('/admin/usuario/' . $usuario->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-6">
                <div class="mb-8">
                    <flux:heading level="2" size="lg" class="mb-4 text-blue-600">Datos de Cuenta
                    </flux:heading>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="mb-4">
                            <flux:label>Rol del usuario <span class="text-red-500">(*)</span></flux:label>
                            <flux:select name="rol" required>
                                @foreach ($roles as $rol)
                                    <flux:select.option value="{{ $rol->name }}"
                                        :selected="old('rol', $usuario->roles->first()->name ?? '') == $rol->name">
                                        {{ $rol->name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>

                        <div class="mb-4">
                            <flux:label>Nombre del Usuario <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="name" type="text" icon="user" placeholder="Nombre completo"
                                required value="{{ old('name', $usuario->name) }}" />
                            <flux:error name="name" />
                        </div>

                        <div class="mb-4">
                            <flux:label>Email <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="email" type="email" icon="envelope" required
                                value="{{ old('email', $usuario->email) }}" />
                            <flux:error name="email" />
                        </div>

                        {{-- En edición, eliminamos el atributo 'required' de las contraseñas --}}
                        <div class="mb-4">
                            <flux:label>Contraseña <span class="text-xs text-slate-400">(Dejar en blanco para no
                                    cambiar)</span></flux:label>
                            <flux:input name="password" type="password" icon="key" placeholder="••••••••" />
                            <flux:error name="password" />
                        </div>

                        <div class="mb-4">
                            <flux:label>Confirmar Contraseña</flux:label>
                            <flux:input name="password_confirmation" type="password" icon="key"
                                placeholder="••••••••" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6">
                <div class="flex space-x-3">
                    <a href="{{ url('/admin/usuarios') }}"
                        class="px-5 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </a>
                    <flux:button variant="primary" type="submit" color="green" class="px-5 cursor-pointer">
                        <i class="fas fa-sync-alt mr-2"></i> Actualizar Usuario
                    </flux:button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('foto-input').addEventListener('change', function() {
            const file = this.files[0];
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('placeholder-icon');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-layouts.app>
