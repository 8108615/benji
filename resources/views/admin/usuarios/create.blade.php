<x-layouts.app title="Registrar Nuevo Usuario">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Nuevo Usuario</flux:heading>
        <p class="text-slate-500 dark:text-neutral-400">Crea una nueva cuenta de usuario en el sistema.</p>
        <br>
        <flux:separator variant="subtle" />
    </div>

    {{-- Card Principal --}}
    <div
        class="bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">

        <form action="{{ url('/admin/usuarios/create') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6">
                <div class="mb-8">
                    <flux:heading level="2" size="lg" class="mb-4 text-blue-600">Datos de Cuenta
                    </flux:heading>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="mb-4">
                            <flux:label>Rol del usuario <span class="text-red-500">(*)</span></flux:label>
                            <flux:select placeholder="Seleccione un rol..." name="rol" required>
                                @foreach ($roles as $rol)
                                    <flux:select.option value="{{ $rol->name }}"
                                        :selected="old('rol') ?? '' == $rol->name">{{ $rol->name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>

                        <div class="mb-4">
                            <flux:label>Nombre del Usuario <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="name" type="text" icon="user" placeholder="Nombre completo"
                                required value="{{ old('name') }}" />
                            <flux:error name="name" />
                        </div>

                        <div class="mb-4">
                            <flux:label>Email <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="email" type="email" icon="envelope" placeholder="correo@ejemplo.com"
                                required value="{{ old('email') }}" />
                            <flux:error name="email" />
                        </div>

                        <div class="mb-4">
                            <flux:label>Contraseña <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="password" type="password" icon="key" placeholder="••••••••"
                                required />
                            <flux:error name="password" />
                        </div>

                        <div class="mb-4">
                            <flux:label>Confirmar Contraseña <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="password_confirmation" type="password" icon="key"
                                placeholder="••••••••" required />
                            <flux:error name="password_confirmation" />
                        </div>
                    </div>
                </div>


            </div>

            {{-- Footer con Botones --}}
            <div class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6">
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
