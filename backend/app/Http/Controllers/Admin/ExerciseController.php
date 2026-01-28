<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index()
    {
        $exercises = Exercise::paginate(10);
        return view('admin.exercises.index', compact('exercises'));
    }

    public function create()
    {
        return view('admin.exercises.create');
    }

   public function store(Request $request)
{
    // 1. Validamos que el nombre sea obligatorio
    $request->validate([
        'name' => 'required',
        // Puedes añadir más validaciones si quieres, por ejemplo:
        // 'muscle_group' => 'required',
    ]);

    // 2. Preparamos los datos
    $data = $request->all();

    // 3. LA CURA: Asignamos manualmente un 0 al wger_id para que MySQL no explote
    // (Asumimos que 0 significa "Ejercicio creado manualmente")
    $data['wger_id'] = 0;

    // 4. Creamos el ejercicio
    // Asegúrate de usar $data y no $request->all()
    Exercise::create($data);

    // 5. Redirigimos
    return redirect()->route('admin.exercises.index')
        ->with('success', 'Ejercicio creado correctamente.');
}

    public function edit($id)
    {
        $exercise = Exercise::findOrFail($id);
        return view('admin.exercises.edit', compact('exercise'));
    }

    public function update(Request $request, $id)
    {
        $exercise = Exercise::findOrFail($id);
        $exercise->update($request->all());
        return redirect()->route('admin.exercises.index')->with('success', 'Actualizado.');
    }

    public function destroy($id)
    {
        Exercise::destroy($id);
        return redirect()->route('admin.exercises.index')->with('success', 'Eliminado.');
    }
}