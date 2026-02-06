<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $categorias = Categoria::query();
        
        if ($buscar) {
            $categorias->where('nombre', 'like', '%' . $buscar . '%');
        }
        $categorias = $categorias->paginate(10);
        return view('admin.categorias.index', compact('categorias','buscar'));



    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return response()->json($request->all());
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
        ]);
        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->save();
        return redirect()->route('admin.categorias.index')
            ->with('mensaje','Categoría creada exitosamente')
            ->with('icono','success');

    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //return response()->json($request->all());
        $validate = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:categorias,nombre,'.$id,
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id',$id);
        }

        $categoria = Categoria::findOrFail($id);
        $categoria->nombre = $request->nombre;
        $categoria->save();
        return redirect()->route('admin.categorias.index')
            ->with('mensaje','Categoría Actualizada Exitosamente')
            ->with('icono','success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();
        return redirect()->route('admin.categorias.index')
            ->with('mensaje','Categoría Eliminada Exitosamente')
            ->with('icono','success');
    }
}
