<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AjusteController extends Controller
{
    public function index(){

        $ajuste = Ajuste::first();

        $jsonData = file_get_contents('https://api.hilariweb.com/divisas');
        $divisas = json_decode($jsonData, true);
        //return response()->json($divisas);
        return view('admin.ajustes.index', compact('divisas', 'ajuste'));
    }

    public function store(Request $request){

        //validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'divisa' => 'required|string|max:10',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'web' => 'nullable|string|max:255',
            'interes' => 'nullable|numeric|min:0|max:100',
            'mora' => 'nullable|numeric|min:0|max:100',
            'dias_gracia' => 'required|integer|min:0',
            'dias_notificacion' => 'required|integer|min:0',
        ]);

        $ajusteExistente = Ajuste::first();

        if ($ajusteExistente) {
            //echo "Ya existe un ajuste registrado.";
            //actualizar los datos
            $ajusteExistente->nombre = $request->nombre;
            $ajusteExistente->descripcion = $request->descripcion;
            $ajusteExistente->direccion = $request->direccion;
            $ajusteExistente->telefono = $request->telefono;
            $ajusteExistente->email = $request->email;
            $ajusteExistente->divisa = $request->divisa;

            if ($request->hasFile('logo')) {
                //eliminar el logo anterior si existe
                if ($ajusteExistente->logo) {
                    Storage::disk('public')->delete($ajusteExistente->logo);
                }
                $logoPath = $request->file('logo')->store('logos', 'public');
                $ajusteExistente->logo = $logoPath;
            }

            $ajusteExistente->web = $request->web;
            $ajusteExistente->interes = $request->interes ?? 10;
            $ajusteExistente->mora = $request->mora ?? 2;
            $ajusteExistente->dias_gracia = $request->dias_gracia;
            $ajusteExistente->dias_notificacion = $request->dias_notificacion;
            $ajusteExistente->save();

            return redirect()->route('admin.ajustes.index')
            ->with('mensaje', 'Ajustes guardados correctamente')
            ->with('icono', 'success');

        }else{
            //echo "No existe un ajuste registrado.";
            //guardar los datos
            $ajuste = new Ajuste();
            $ajuste->nombre = $request->nombre;
            $ajuste->descripcion = $request->descripcion;
            $ajuste->direccion = $request->direccion;
            $ajuste->telefono = $request->telefono;
            $ajuste->email = $request->email;
            $ajuste->divisa = $request->divisa;

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
                $ajuste->logo = $logoPath;
            }

            $ajuste->web = $request->web;
            $ajuste->interes = $request->interes ?? 10;
            $ajuste->mora = $request->mora ?? 2;
            $ajuste->dias_gracia = $request->dias_gracia;
            $ajuste->dias_notificacion = $request->dias_notificacion;
            $ajuste->save();

            return redirect()->route('admin.ajustes.index')
            ->with('mensaje', 'Ajustes guardados correctamente')
            ->with('icono', 'success');
        }








    }
}
