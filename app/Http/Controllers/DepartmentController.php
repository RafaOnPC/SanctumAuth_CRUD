<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartamentRequest;
use App\Models\Departament;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Retornando los registros de departamentos
        try {
            $departament = Departament::all();
            return response()->json($departament,200);
        } catch (Exception $e) {
            return response()
            ->json(['status'=> false, 'message' => 'No se encontraron registros en la Base de datos'], 404);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartamentRequest $request)
    {
        //Recibir y verificar parametros
        $name = $request->input('name');
        try {
            //Crear un nuevo registro
            $department = new Departament();
            $department->name = $name;
            $department->save();

            return response()->json([
                'status' => true,
                'message' => 'Departamento agregado exitosamente'
            ],200);
        } catch (Exception $e) {
            return response()->json(['status'=> false, 'message' => 'Departamento no agregado'], 400);
        }
    }

    public function show(String $id)
    {
        try {
            $departament = Departament::findOrFail($id);
            return response()->json(['status'=> true, 'data' => $departament],200);
        } catch (Exception $e) {
            //return response()->json(['status'=> false, 'message' => $e->getMessage()], 404);
            return response()->json(['status'=> false, 'message' => 'No se encontro el departamento'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartamentRequest $request, String $id)
    {
       //Recibir y verificar parametros
       $name = $request->input('name');
       try {
        //Encontrar el registro para actualizar
            $department = Departament::findOrFail($id);
            $department->name = $name;
            $department->update();
            return response()->json(['status'=> true, 'message' => 'Departamento actualizado exitosamente'],200);
       } catch (Exception $e) {
            return response()->json(['status'=> false, 'message' => 'No se encontro al departamento'], 404);
       }
            
    }
       
    public function destroy(String $id)
    {
        try {
            $departament = Departament::findOrFail($id);
            $departament->delete();
            return response()->json([
                'status' => true,
                'message' => 'Departamento eliminado exitosamente'
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Departamento no encontrado'
            ],404);
        }
      
    }
}
