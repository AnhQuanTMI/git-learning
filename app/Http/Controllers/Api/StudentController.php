<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('name')->paginate(10);
        
        return response()->json([
            'status' => true,
            'message' => 'Students retrieved successfully',
            'data' => $students->items(),
            'meta' => [
                'current_page' => $students->currentPage(),
                'total_pages' => $students->lastPage(),
                'total_items' => $students->total(),
            ]
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
        ]);

        $student = Student::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Student created successfully',
            'data' => $student
        ], Response::HTTP_CREATED);
    }

    public function show(Student $student)
    {
        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not found',
                'error' => 'Student with the given ID does not exist'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => true,
            'message' => 'Student details retrieved successfully',
            'data' => $student
        ], Response::HTTP_OK);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:students,email,' . $student->id,
        ]);

        $student->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Student updated successfully',
            'data' => $student
        ], Response::HTTP_OK);
    }

    public function destroy(Student $student)
    {
        $deleted = $student->delete();
        
        if (!$deleted) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to delete student',
                'error' => 'Student not found or could not be deleted'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'message' => 'Student deleted successfully',
            'data' => null
        ], Response::HTTP_NO_CONTENT);
    }
}
