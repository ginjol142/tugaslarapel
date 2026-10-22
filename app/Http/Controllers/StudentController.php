<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with('classroom')
            ->when($request->classroom_id, fn($q, $id) =>
                $q->where('classroom_id', $id))
            ->when($request->status, fn($q, $status) =>
                $q->where('status', $status))
            ->paginate(10);

        return view('students.index', compact('students'));
    }

    public function create()
    {
        $classrooms = Classroom::all();
        return view('students.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'nis' => 'required|string|max:20|unique:students,nis',
            'name' => 'required|string|max:100',
            'gender' => ['required', Rule::in(['L', 'P'])],
            'birth_date' => 'nullable|date|before_or_equal:today',
            'status' => ['required', Rule::in(['aktif', 'lulus', 'pindah'])],
        ]);

        Student::create($data);
        return redirect()->route('students.index');
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classrooms = Classroom::all();
        return view('students.edit', compact('student', 'classrooms'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'nis' => [
                'required', 'string', 'max:20',
                Rule::unique('students', 'nis')->ignore($student->id),
            ],
            'name' => 'required|string|max:100',
            'gender' => ['required', Rule::in(['L', 'P'])],
            'birth_date' => 'nullable|date|before_or_equal:today',
            'status' => ['required', Rule::in(['aktif', 'lulus', 'pindah'])],
        ]);

        $student->update($data);
        return redirect()->route('students.index');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index');
    }
}