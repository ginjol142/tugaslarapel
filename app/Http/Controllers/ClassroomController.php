<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::withCount('students')->paginate(10);
        return view('classrooms.index', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'grade_level' => 'required|integer|min:1|max:255',
        ]);

        Classroom::create($data);
        return redirect()->route('classrooms.index');
    }

    public function destroy(Classroom $classroom)
    {
        if ($classroom->students()->exists()) {
            return back()->with(
                'error', 'Kelas tidak bisa dihapus karena masih memiliki siswa.'
            );
        }

        $classroom->delete();
        return redirect()->route('classrooms.index');
    }
}