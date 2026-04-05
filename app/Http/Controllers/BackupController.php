<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk('local');
        $backupPath = trim((string) config('backup.backup.name', ''), '/');

        try {
            $files = $disk->files($backupPath);
        } catch (\Throwable $e) {
            return view('admin.backups.index', ['backups' =>[]])
                ->with('mensaje', 'No se pudo listar la carpeta de Bacvkups. Verifica permisos en storage/app/private.')
                ->with('icono', 'error');
        }
        $backups = collect($files)
            ->filter(fn(string $path) => str_ends_with($path, '.zip'))
            ->map(function (string $path) use ($disk) {
                return [
                    'name' => basename($path),
                    'path' => $path,
                    'size' => $disk->size($path),
                    'last_modified' => $disk->lastModified($path),
                ];
            })
            ->sortByDesc('last_modified')
            ->values();
        return view('admin.backups.index', compact('backups'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        try {
            Artisan::call('backup:run');
            $copiasEliminadas = $this->cleanupOldBackups();

            $mensaje = 'Backup creado exitosamente.';
            if ($copiasEliminadas > 0) {
                $mensaje .= " Se ha alacanzado el limites de 5 copias; la copia mas antigua se ha eliminado ({$copiasEliminadas}) para liberar espacio.";

            }

            if($request->expectsJson()) {
                return response()->json([
                    'OK' => true,
                    'message' => $mensaje,
                    'icon' => 'success',
                ]);
            }

            return redirect()->route('admin.backups.index')
                ->with('mensaje', $mensaje)
                ->with('icono', 'success');
        } catch (\Throwable $e) {
            if($request->expectsJson()) {
                return response()->json([
                    'OK' => false,
                    'message' => 'Error al crear el backup',
                    'error' => $e->getMessage(),
                    'icon' => 'error',
                ], 500);
            }
            return redirect()->route('admin.backups.index')
                ->with('mensaje', 'Error al crear el backup: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    public function download($file)
    {
        $safeFile = basename($file);
        $path = $this->resolveBackupPath($safeFile);

        if (!$path) {
            return redirect()->route('admin.backups.index')
                ->with('mensaje', 'El Backup solicitado no existe.')
                ->with('icono', 'error');
        }
        return response()->download(Storage::disk('local')->path($path), $safeFile);
    }

    public function destroy($file)
    {
        $safeFile = basename($file);
        $path = $this->resolveBackupPath($safeFile);

        if (!$path) {
            return redirect()->route('admin.backups.index')
                ->with('mensaje', 'El Backup solicitado no existe.')
                ->with('icono', 'error');
        }
        Storage::disk('local')->delete($path);

            return redirect()->route('admin.backups.index')
                ->with('mensaje', 'Backup Eliminado Correctamente.')
                ->with('icono', 'success');
    }

    public function resolveBackupPath($file)
    {
        $disk = Storage::disk('local');
        $backupPath = trim((string) config('backup.backup.name', ''), '/');
        $path = $backupPath . '/' . $file;

        if ($disk->exists($path)) {
            return $path;
        }
        return $path;
    }

    private function cleanupOldBackups(int $maximosBackups = 5): int
    {
        $disco = Storage::disk('local');
        $rutaBackup = trim((string) config('backup.backup.name', ''), '/');

        if (!$disco->exists($rutaBackup)) {
            return 0;
        }

        $copiaZip = collect($disco->files($rutaBackup))
            ->filter(fn(string $archivo) => str_ends_with(strtolower($archivo), '.zip'))
            ->sortByDesc(fn(string $archivo) => $disco->lastModified($archivo));

        $copiasParaEliminar = $copiaZip->slice($maximosBackups);
        $eliminadas = 0;
        foreach ($copiasParaEliminar as $ruta) {
            $disco->delete($ruta);
            $eliminadas++;
        }
        return $eliminadas;
    }
}
