<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Students",
 *     description="API Endpoints for student management"
 * )
 */
class StudentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/students",
     *     summary="Get paginated list of students",
     *     tags={"Students"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Students retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Students retrieved successfully"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="total_pages", type="integer", example=5),
     *                 @OA\Property(property="total_items", type="integer", example=50)
     *             )
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/students",
     *     summary="Create a new student",
     *     tags={"Students"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Student created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Student created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/v1/students/{id}",
     *     summary="Get student details",
     *     tags={"Students"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Student ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Student details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Student details retrieved successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student not found"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/v1/students/{id}",
     *     summary="Update student details",
     *     tags={"Students"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Student ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Student updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Student updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/v1/students/{id}",
     *     summary="Delete a student",
     *     tags={"Students"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Student ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Student deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student not found"
     *     )
     * )
     */
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
