<?php

namespace App\Http\Controllers;

use App\Models\LessonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonPeriodController extends Controller
{
    private function getSchool()
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        return $admin->school;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $school = $this->getSchool();

        $lessonPeriods = LessonPeriod::where('school_id', $school->id)->orderBy('jam_ke')->get();

        return view('admin.lesson_periods.index', compact('lessonPeriods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.lesson_periods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $school = $this->getSchool();
        
        $validated = $request->validate([
            'jam_ke' => 'required|integer|min:1',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $exists = LessonPeriod::where('school_id', $school->id)->where('jam_ke', $validated['jam_ke'])->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Lesson period with this jam_ke already exists.');
        }

        LessonPeriod::create([
            'school_id' => $school->id,
            'jam_ke' => $validated['jam_ke'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'is_active' => true,
        ]);

        return redirect()->route('lesson-periods.index')->with('success', 'Lesson period created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LessonPeriod $lessonPeriod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LessonPeriod $lessonPeriod)
    {
        $school = $this->getSchool();

        if ($lessonPeriod->school_id != $school->id) {
            abort(404);
        }

        return view('admin.lesson_periods.edit',compact('lessonPeriod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LessonPeriod $lessonPeriod)
    {
        $school = $this->getSchool();

        if ($lessonPeriod->school_id != $school->id) {
            abort(404);
        }

        $validated = $request->validate([
            'jam_ke' => 'required|integer|min:1',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $exists = LessonPeriod::where('school_id', $school->id)->where('jam_ke', $validated['jam_ke'])->where('id', '!=', $lessonPeriod->id)->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Lesson period with this jam_ke already exists.');
        }

        $lessonPeriod->update($validated);

        return redirect()->route('lesson-periods.index')->with('success', 'Lesson period updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LessonPeriod $lessonPeriod)
    {
        $school = $this->getSchool();

        if ($lessonPeriod->school_id != $school->id) {
            abort(404);
        } 

        $lessonPeriod->delete();

        return redirect()->route('lesson-periods.index')->with('success', 'Lesson period deleted successfully.');
    }
}
