<x-layouts.app :title="'404 - Página no encontrada'">
    <div class="mx-auto flex min-h-[80vh] max-w-7xl items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md text-center">
            <br><br><br>
            <div class="mb-6 flex justify-center">
                <div class="rounded-full bg-amber-50 px-4 dark:bg-amber-900/20">
                    <flux:icon name="exclamation-triangle" class="h-12 w-12 text-amber-400 dark:text-amber-400" />
                </div>
            </div>

            <h1 class="text-8xl font-black text-zinc-800 dark:text-white" style="font-size: 20pt">404</h1>
            <h2 class="mt-2 text-xl font-semibold text-zinc-600 dark:text-zinc-400">Página no encontrada</h2>

            <p class="mx-auto mt-4 max-w-sm text-base text-zinc-500 dark:text-zinc-500">
                La página que estás buscando no existe.
            </p>

            <br>

            <div class="mt-8 rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-800 dark:bg-zinc-900/50">
                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    <span class="font-medium text-zinc-900 dark:text-zinc-400">¿Que puedes hacer?</span> <br>
                    Verifica que la URL sea correcta o regresa a la página anterior

                </p>
                <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-500">
                    La pagina podria haber sido movida o eliminada. Si crees que esto es un error, contacta al Soporte..
                </p>
            </div>

            <br>

            <div class="mt-8 flex w-full justify-center">
                <div class="flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-5 py-2.5 text-sm font-medium text-zinc-700 transition">
                        <flux:icon name="arrow-left" class="h-4 w-4" />
                        Volver
                    </a>

                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-5 py-2.5 text-sm font-medium text-white transition-color hover:bg-blue-600">
                        <flux:icon name="home" class="h-4 w-4" />
                        Ir al Inicio
                    </a>
                </div>
            </div>

            <div class="mt-6">
                <a href="#"
                class="text-sm text-zinc-400 underline-offset hover:text-zinc-600 hover:underline dark:text-zinc-500 dark:hover:text-zinc-300">
                    Contactar al Soporte ->
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
