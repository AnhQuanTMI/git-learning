<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return response()->json($students);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
        ]);

        $student = Student::create($validated);
        return response()->json($student, 201);
    }

    public function show(Student $student)
    {
        return response()->json($student);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:students,email,' . $student->id,
        ]);

        $student->update($validated);
        return response()->json($student);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(null, 204);
    }
}
