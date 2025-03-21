<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('');
    }

    public function list(Request $request): JsonResponse
    {
        
        
        $categorias = Categoria::query()->with(Str::of('subCategorias')->append(Str::repeat('.subCategorias', $request->get('profundidade', 10)))->toString())
                ->whereNull('pai_id')    
                ->paginate($request->get('perPage', 10), ['*'],'page' ,$request->get('page', 1));

        return response()->json($categorias);
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
    public function store(StoreCategoriaRequest $request): JsonResponse
    {
        try{
        
            $categoria = Categoria::query()->create($request->validated());
         
            return response()->json($categoria);
        }catch(Exception $e){
           
            return response()->json(['message' => 'Erro no servidor'], 500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Categoria $categoria): JsonResponse
    {
        $categoria = $categoria->when($request->exists('include'),fn($query)=> $query->with(explode(',', $request->get('include', ''))))->first();

        return response()->json($categoria);
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
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {

        try{
            
            $categoria->update($request->only(['nome', 'descricao']));

            return response()->json($categoria);
        }catch(Exception $e){
            return response()->json(['message' => 'Erro no servidor'], 500);
        }
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        try{
            
            $categoria->delete();
        
            return response()->json(['message' => 'Categoria deletada']);
        }catch(Exception $e){
            return response()->json(['message' => 'Erro no servidor'], 500);
        }
    }
        
}
