<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();

        return inertia('Students/Index', [
            'students' => $students,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Students/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'last_name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'mi' => 'nullable|string|max:1',
        'gender' => 'required|string|in:male,female',
        'birth_date' => 'required|date',
        'birth_place' => 'nullable|string|max:255',
        'address' => 'required|string|max:255',
        'father' => 'nullable|string|max:255',
        'mother' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:15',
    ]);

    $student = Student::create($validatedData);

    return redirect('/students/' . $student->id)->with('success', 'Student created successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return inertia('Students/Show', [
        'student' => $student,
    ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return inertia('Students/Edit', [
            'student' => $student,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'mi' => 'nullable|string|max:1',
            'gender' => 'required|string|in:male,female',
            'birth_date' => 'required|date',
            'birth_place' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'father' => 'nullable|string|max:255',
            'mother' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
