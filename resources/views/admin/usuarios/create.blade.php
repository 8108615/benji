<x-layouts.app title="Registrar Nuevo Usuario">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Nuevo Usuario</flux:heading>
        <p class="text-slate-500 dark:text-neutral-400">Crea una nueva cuenta de Usuario</p>
        <br>
        <flux:separator variant="subtle" />
    </div>

    {{-- card principal --}}

    <div
        class="bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg transition-all duration-300 hover:shadow-xl">

        <form action="{{ url('/admin/usuarios/create') }}" method="POST" enctype="multipart/form-data">
            @csrf
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
                                        :selected="old('rol') ?? '' == $rol->name">{{ $rol->name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                        <div class="mb-4">
                            <flux:label>Email <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="email" icon="envelope" placeholder="Ej: emorales@gmail.com..." required
                                value="{{ old('email') }}" />
                            <flux:error name="email" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Contraseña <span class="text-red-500">(*)</span></flux:label>
                            <flux:input type="password" name="password" icon="key" placeholder="Ej: ........."
                                required value="{{ old('password') }}" />
                            <flux:error name="password" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Confirmar Contraseña <span class="text-red-500">(*)</span></flux:label>
                            <flux:input type="password" name="password_confirmation" icon="key"
                                placeholder="Ej: ........." required value="{{ old('password_confirmation') }}" />
                            <flux:error name="password_confirmation" />
                        </div>
                    </div>
                </div>

                <flux:separator variant="subtle" />

                <div class="mb-8">
                    <flux:heading level="2" size="lg" class="mb-4 text-blue-600">Informacion Personales
                    </flux:heading>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="mb-4">
                            <flux:label>Nombres <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="nombres" icon="user" placeholder="Ej: Erick Manuel..." required
                                value="{{ old('nombres') }}" />
                            <flux:error name="nombres" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Apellidos <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="apellidos" placeholder="Ej: Morales Lopez..." required
                                value="{{ old('apellidos') }}" />
                            <flux:error name="apellidos" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Tipo Documento <span class="text-red-500">(*)</span></flux:label>
                            <flux:select name="tipo_documento" required>
                                <option value="" disabled selected> Seleccione...</option>
                                <option value="DNI">DNI</option>
                                <option value="Pasaporte">Pasaporte</option>
                                <option value="Carnet de Extranjería">Carnet de Extranjería</option>
                                <option value="RUC">RUC</option>
                                <option value="Carnet de identidad">Carnet de Identidad</option>
                            </flux:select>
                        </div>
                        <div class="mb-4">
                            <flux:label>Nro Documento <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="numero_documento" icon="identification" placeholder="Ej: 12345678..."
                                required value="{{ old('numero_documento') }}" />
                            <flux:error name="numero_documento" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Celular <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="celular" icon="phone" placeholder="Ej: 999 999 999" required
                                value="{{ old('celular') }}" />
                            <flux:error name="celular" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Fecha Nacimiento <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="fecha_nacimiento" icon="calendar" type="date" required
                                value="{{ old('fecha_nacimiento') }}" />
                            <flux:error name="fecha_nacimiento" />
                        </div>
                        <div class="mb-4">
                            <flux:label>Género <span class="text-red-500">(*)</span></flux:label>
                            <flux:select name="genero" required>
                                <option value="" disabled selected> Seleccione...</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </flux:select>
                        </div>
                        <div class="mb-4">
                            <flux:label>Estado <span class="text-red-500">(*)</span></flux:label>
                            <flux:select name="estado" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </flux:select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <flux:label>Direccion de Domicilio <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="direccion" icon="map-pin" placeholder="Ej: Calle Principal, Ciudad..."
                            required value="{{ old('direccion') }}" />
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
                            <flux:input name="contacto_nombre" placeholder="Ej: Juan Pérez" required value="{{ old('contacto_nombre') }}" />
                            <flux:label>Teléfono del Contacto<span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="contacto_telefono" placeholder="Ej: 987654321" required value="{{ old('contacto_telefono') }}" />
                            <flux:label>Relación con el Contacto<span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="contacto_relacion" placeholder="Ej: Padre, Madre, Amigo..." required value="{{ old('contacto_relacion') }}" />
                        </div>
                    </div>
                    {{-- Foto de Perfil --}}
                    <div>
                        <flux:heading level="2" size="lg" class="mb-4 text-blue-600">Foto de Perfil
                        </flux:heading>
                        <div class="flex items-center gap-4">
                            <div class="relative group">
                                <div
                                    class="h-24 rounded-full border-2 border-dashed border-slate-300 overflow-hidden bg-slate-50 flex items-center justify-center">
                                    <img id="image-preview" src="#" alt="preview"
                                        class="h-full w-full object-cover">
                                    <flux-icon id="placeholder-icon" name="user"
                                        class="text-slate-300 h-10 w-10" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="foto_perfil" id="foto-input" class="hidden"
                                    accept="image/*">
                                <label for="foto-input">
                                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold hover:bg-slate-500">
                                    <flux-icon name="cloud-arrow-up" class="text-gray-600" variant="micro" />
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

            {{-- footer con Botones --}}

            <div
                class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-neutral-700 rounded-b-lg p-6">
                <div class="flex space-x-3">
                    <a href="{{ url('/admin/usuarios') }}"
                        class="px-5 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                    <flux:button variant="primary" type="submit" color="blue" class="px-5 cursor-pointer">
                        <i class="fas fa-save mr-2"></i> Registrar Usuario
                    </flux:button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('foto-input').addEventListener('change', function(){
            const file = this.files[0];
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElemntById('placeholder-icon');
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


    <div class="py-4 flex justify-center">

    </div>




</x-layouts.app>
