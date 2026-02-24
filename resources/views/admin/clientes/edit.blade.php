<x-layouts.app title="Modificar Cliente">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ url('/admin') }}">Inicio</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ url('/admin/clientes') }}">Listado de Clientes</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Modificar datos del cliente: {{ $cliente->apellidos }} {{ $cliente->nombres }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <br>
    <flux:separator variant="subtle" />

    {{-- Card Principal --}}
    <div
        class="bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">

        <form action="{{ url('/admin/cliente/' . $cliente->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-6">
                <div class="mb-8">
                    <flux:heading level="2" size="lg" class="mb-4 text-blue-600">Datos de Cuenta
                    </flux:heading>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">


                        <div class="mb-4">
                            <flux:label>Email <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="email" type="email" icon="envelope" placeholder="correo@ejemplo.com"
                                required value="{{ old('email', $cliente->user->email) }}" />
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

                <flux:separator variant="subtle" class="my-6" />

                <div class="mb-8">
                    <flux:heading level="2" size="lg" class="mb-4 text-blue-600">Información Personal
                    </flux:heading>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="mb-4">
                            <flux:label>Nombres <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="nombres" placeholder="Nombres" required value="{{ old('nombres', $cliente->nombres) }}" />
                            <flux:error name="nombres" />
                        </div>

                        <div class="mb-4">
                            <flux:label>Apellidos <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="apellidos" placeholder="Apellidos" required
                                value="{{ old('apellidos', $cliente->apellidos) }}" />
                            <flux:error name="apellidos" />
                        </div>

                        <div class="mb-4">
                            <flux:label>Tipo Documento <span class="text-red-500">(*)</span></flux:label>
                            <flux:select name="tipo_documento" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="DNI" {{ old('tipo_documento', $cliente->tipo_documento) == 'DNI' ? 'selected' : '' }}>DNI</option>
                                <option value="Pasaporte" {{ old('tipo_documento', $cliente->tipo_documento) == 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                                <option value="Carnet de Extranjería" {{ old('tipo_documento', $cliente->tipo_documento) == 'Carnet de Extranjería' ? 'selected' : '' }}>Carnet de Extranjería</option>
                                <option value="RUC" {{ old('tipo_documento', $cliente->tipo_documento) == 'RUC' ? 'selected' : '' }}>RUC</option>
                                <option value="Carnet de identidad" {{ old('tipo_documento', $cliente->tipo_documento) == 'Carnet de identidad' ? 'selected' : '' }}>Carnet de identidad</option>
                            </flux:select>
                        </div>

                        <div class="mb-4">
                            <flux:label>Nro Documento <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="numero_documento" icon="identification" placeholder="12345678" required
                                value="{{ old('numero_documento', $cliente->numero_documento) }}" />
                            <flux:error name="numero_documento" />
                        </div>

                        <div class="mb-4">
                            <flux:label>Celular <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="celular" icon="phone" placeholder="999 999 999" required
                                value="{{ old('celular', $cliente->celular) }}" />
                            <flux:error name="celular" />
                        </div>

                        <div class="mb-4">
                            <flux:label>Fecha Nacimiento <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="fecha_nacimiento" type="date" required
                                value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento) }}" />
                            <flux:error name="fecha_nacimiento" />
                        </div>

                        <div class="mb-4">
                            <flux:label>Género <span class="text-red-500">(*)</span></flux:label>
                            <flux:select name="genero" required>
                                <option value="Masculino" {{ old('genero', $cliente->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero', $cliente->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            </flux:select>
                        </div>

                        <div class="mb-4">
                            <flux:label>Estado <span class="text-red-500">(*)</span></flux:label>
                            <flux:select name="estado">
                                <option value="Activo" {{ old('estado', $cliente->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ old('estado', $cliente->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </flux:select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <flux:label>Dirección de Domicilio <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="direccion" icon="map-pin" placeholder="Av. Siempre viva 123..." required
                            value="{{ old('direccion', $cliente->direccion) }}" />
                    </div>
                </div>

                <flux:separator variant="subtle" class="my-6" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Contacto de Emergencia --}}
                    <div>
                        <flux:heading level="2" size="lg" class="mb-4 text-blue-600">Contacto de Emergencia
                        </flux:heading>
                        <div class="space-y-4">
                            <flux:label>Nombre Completo<span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="contacto_nombre" placeholder="Ej: María Pérez" required
                                value="{{ old('contacto_nombre', $cliente->contacto_nombre) }}" />
                            <flux:label>Teléfono de contacto <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="contacto_telefono" placeholder="987 654 321" required
                                value="{{ old('contacto_telefono', $cliente->contacto_telefono) }}" />
                            <flux:label>Relación / Parentesco <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="contacto_relacion" placeholder="Ej: Madre, Cónyuge" required
                                value="{{ old('contacto_relacion', $cliente->contacto_relacion) }}" />
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
                                    {{-- Lógica para mostrar la foto actual --}}
                                    <img id="image-preview"
                                        src="{{ $cliente->foto_perfil ? asset('storage/' . $cliente->foto_perfil) : '#' }}"
                                        alt="Preview"
                                        class="{{ $cliente->foto_perfil ? '' : 'hidden' }} h-full w-full object-cover">

                                    <flux:icon id="placeholder-icon" name="user"
                                        class="{{ $cliente->foto_perfil ? 'hidden' : '' }} text-slate-300 h-10 w-10" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="foto_perfil" id="foto-input" class="hidden"
                                    accept="image/*">
                                <label for="foto-input"
                                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold hover:bg-slate-50 transition-all">
                                    <flux:icon name="cloud-arrow-up" class="text-gray-600" variant="micro" />
                                    <span class="text-gray-600">Subir Foto</span>
                                </label>
                                <p id="file-name" class="text-xs text-slate-400 mt-2 italic">Formatos: JPG, PNG (Max.
                                    2MB)</p>
                            </div>
                        </div>
                        <flux:error name="foto_perfil" />
                    </div>
                </div>
            </div>

            {{-- Footer con Botones --}}
            <div class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6">
                <div class="flex space-x-3">
                    <a href="{{ url('/admin/clientes') }}"
                        class="px-5 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                    <flux:button variant="primary" type="submit" color="green" class="px-5 cursor-pointer">
                        <i class="fas fa-save mr-2"></i> Actualizar Cliente
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
