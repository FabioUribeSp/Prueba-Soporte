<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Crear tarea
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:500',
            'user' => 'required|email',// Cambiado para validar un correo electrónico.
        ]);

        $user = User::where('email', $validated['user'])->first();
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        $task = new Task($validated);
        $task->user_id = $user->id;
        $task->save();

        return redirect()->back()->with('success', 'Task created successfully.');
    }

    // Actualizar tarea
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:500',
        ]);
    
        $task = Task::findOrFail($id); // Lanzará un 404 si no se encuentra la tarea.
    
        $task->update($validated);
        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    // Eliminar tarea
    public function destroy($id)
    {
        $task = Task::findOrFail($id); // Lanza una excepción si no se encuentra la tarea.
        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
