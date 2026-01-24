<x-layouts.app title="Editar Usuario: {{ $usuario->nombres }}">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ url('/admin/usuarios') }}">Listado de Usuarios</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Modificar Datos del Usuario: {{ $usuario->name }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <br>
    <flux:separator variant="subtle" />


    {{-- card principal --}}
    <div class="bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg shadow-xl">

        <form action="{{ url('/admin/usuario/' . $usuario->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            {{-- Body --}}
            <div class="p-6">
                <div class="mb-8">
                    <flux:heading level="2" size="lg" class="mb-4 text-blue-600">Datos de la Cuenta
                    </flux:heading>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="mb-4">
                            <flux:label>Rol del Usuario <span class="text-red-500">(*)</span></flux:label>
                            <flux:select placeholder="Seleccione un Rol..." name="rol" required>
                                @foreach ($roles as $rol)
                                    <flux:select.option value="{{ $rol->name }}"
                                        :selected="old('rol', $usuario->roles->first()->name ?? '') == $rol->name">
                                        {{ $rol->name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                        <div class="mb-4">
                            <flux:label>Email <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="email" icon="envelope" placeholder="Ej: emorales@gmail.com..." required
                                value="{{ old('email', $usuario->email) }}" />
                            <flux:error name="email" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Contraseña <span class="text-xs text-slate-400">(Dejar en Blanco para No
                                    Cambiar)</span></flux:label>
                            <flux:input type="password" name="password" icon="key" placeholder="Ej: ........." />
                            <flux:error name="password" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Confirmar Contraseña</flux:label>
                            <flux:input type="password" name="password_confirmation" icon="key"
                                placeholder="Ej: ........." />
                        </div>
                    </div>
                </div>

                <flux:separator variant="subtle" class="my-6" />

                <div class="mb-8">
                    <flux:heading level="2" size="lg" class="mb-4 text-blue-600">Informacion Personales
                    </flux:heading>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="mb-4">
                            <flux:label>Nombres <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="nombres" required value="{{ old('nombres', $usuario->nombres) }}" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Apellidos <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="apellidos" placeholder="Ej: Morales Lopez..." required
                                value="{{ old('apellidos', $usuario->apellidos) }}" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Tipo Documento <span class="text-red-500">(*)</span></flux:label>
                            <flux:select name="tipo_documento" required>
                                @foreach (['DNI', 'Pasaporte', 'Carnet de Extrangeria', 'RUC', 'Carnet de Identidad'] as $tipo)
                                    <option value="{{ $tipo }}"
                                        {{ old('tipo_documento', $usuario->tipo_documento) == $tipo ? 'selected' : '' }}>
                                        {{ $tipo }}</option>
                                @endforeach
                            </flux:select>
                        </div>
                        <div class="mb-4">
                            <flux:label>Nro Documento <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="numero_documento" icon="identification" required
                                value="{{ old('numero_documento', $usuario->numero_documento) }}" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Celular <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="celular" icon="phone" required
                                value="{{ old('celular', $usuario->celular) }}" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Fecha Nacimiento <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="fecha_nacimiento" type="date" required
                                value="{{ old('fecha_nacimiento', $usuario->fecha_nacimiento) }}" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Género <span class="text-red-500">(*)</span></flux:label>
                            <flux:select name="genero" required>
                                <option value="Masculino"
                                    {{ old('genero', $usuario->genero) == 'Masculino' ? 'selected' : '' }}>Masculino
                                </option>
                                <option value="Femenino"
                                    {{ old('genero', $usuario->genero) == 'Femenino' ? 'selected' : '' }}>Femenino
                                </option>
                            </flux:select>
                        </div>
                        <div class="mb-4">
                            <flux:label>Estado <span class="text-red-500">(*)</span></flux:label>
                            <flux:select name="estado" required>
                                <option value="Activo"
                                    {{ old('estado', $usuario->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo"
                                    {{ old('estado', $usuario->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo
                                </option>
                            </flux:select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <flux:label>Direccion de Domicilio <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="direccion" icon="map-pin" required
                            value="{{ old('direccion', $usuario->direccion) }}" />
                    </div>
                </div>

                <flux:separator variant="subtle" class="my-6" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Contacto de Emergencia --}}
                    <div>
                        <flux:heading level="2" size="lg" class="mb-4 text-blue-600">Contacto de Emergencia
                        </flux:heading>
                        <div class="space-y-4">
                            <flux:label>Nombre Completo <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="contacto_nombre" required
                                value="{{ old('contacto_nombre', $usuario->contacto_nombre) }}" />

                            <flux:label>Teléfono de Contacto <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="contacto_telefono" required
                                value="{{ old('contacto_telefono', $usuario->contacto_telefono) }}" />

                            <flux:label>Relación con el Contacto <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="contacto_relacion" required
                                value="{{ old('contacto_relacion', $usuario->contacto_relacion) }}" />
                        </div>
                    </div>
                    {{-- Foto de Perfil --}}
                    <div>
                        <flux:heading level="2" size="lg" class="mb-4 text-blue-600">Foto de Perfil
                        </flux:heading>

                        <div class="flex items-center gap-4">
                            <div class="relative group">
                                <div
                                    class="h-24 w-24 rounded-full border-2 border-dashed border-slate-300 overflow-hidden bg-slate-50 flex items-center justify-center">
                                    <img id="image-preview"
                                        src="{{ $usuario->foto_perfil ? asset('storage/' . $usuario->foto_perfil) : '#' }}"
                                        alt="Preview"
                                        class={{ $usuario->foto_perfil ? '' : 'hidden' }} "hidden h-full w-full object-cover">
                                    <flux:icon id="placeholder-icon" name="user"
                                        class={{ $usuario->foto_perfil ? 'hidden' : '' }}"text-slate-300 h-10 w-10" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="foto_perfil" id="foto-input" class="hidden"
                                    accept="image/*">
                                <label for="foto-input"
                                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold hover:bg-slate-50 transition-all">
                                    <flux:icon name="cloud-arrow-up" class="text-gray-600" variant="micro" />
                                    <span class="text-gray-600">Cambiar Foto</span>
                                </label>
                                <p id="file-name" class="text-xs text-slate-400 mt-2 italic">Mantener actual o no sube
                                    una Nueva.</p>

                            </div>
                        </div>
                        <flux:error name="foto_perfil" />
                    </div>
                </div>
            </div>

            {{-- footer con Botones --}}

            <div
                class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-neutral-700 rounded-b-lg p-6">
                <div class="flex space-x-3">
                    <a href="{{ url('/admin/usuarios') }}"
                        class="px-5 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i> Cancelar
                    </a>
                    <flux:button variant="primary" type="submit" color="green" class="px-5 cursor-pointer">
                        <i class="fas fa-save mr-2"></i> Actualizar Usuario
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
            const fileName = document.getElementById('file-name');
            if (file) {
                fileName.textContent = file.name;
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
