<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Employee;
use App\Models\Departament;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::select('employees.*','departaments.name as departament')
        ->join('departaments','departaments.id','=','employees.departament_id')->paginate(10);
        return response()->json($employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
    
        // Validar los datos de entrada utilizando el objeto EmployeeRequest
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $departament_id = $request->input('departament_id');

    try {
        //Creando un nuevo registro
        $employee = new Employee();
        $employee->name = $name;
        $employee->email = $email;
        $employee->phone = $phone;
        $employee->departament_id = $departament_id;
        $employee->save();

        return response()->json(['status' => true, 'message' => 'Empleado agregado exitosamente'],200);
    } catch (Exception $e) {
        return response()->json(['status' => false, 'message' => 'Empleado no pudo ser agregado'],400);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return response()->json(['status'=> true, 'data' => $employee],200);
        } catch (Exception $e) {
            return response()->json(['status'=> false, 'message' => 'No se encontro al empleado'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, String $id)
    {
        // Validar los datos de entrada utilizando el objeto EmployeeRequest
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $departament_id = $request->input('departament_id');

    try {
        //Encontrar el registro para actualizar
        $employee = Employee::findOrFail($id);
        $employee->name = $name;
        $employee->email = $email;
        $employee->phone = $phone;
        $employee->departament_id = $departament_id;
        $employee->save();

        return response()->json(['status' => true, 'message' => 'Empleado actualizado exitosamente'],200);
    } catch (Exception $e) {
        return response()->json(['status' => false, 'message' => 'Empleado no encontrado'],404);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();
            return response()->json([
                'status' => true,
                'message' => 'Empleado eliminado exitosamente'
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Empleado no encontrado'
            ],404);
        }
    }

    public function EmployeesByDepartment()
    {
        $employees = Employee::select(DB::raw('count(employees.id) as count, departaments.name'))
        ->rightjoin('departaments','departaments.id','=','employees.departament_id')->groupBy('departaments.name')->get();
        return response()->json($employees);
    }

    public function all()
    {
        $employees = Employee::select('employees.*','departaments.name as departament')
        ->join('departaments','departaments.id','=','employees.departament_id')
        ->get();
        return response()->json($employees);
    }
}
