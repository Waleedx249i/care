<?php

namespace App\Http\Controllers;

use App\Models\WorkingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorWorkingHoursController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;
        $hours = WorkingHour::where('doctor_id', $doctor->id)->orderBy('weekday')->orderBy('start_time')->get();
        return view('doctor.working_hours.index', compact('hours'));
    }

    /**
     * Show edit UI. If doctor id is provided and current user is admin, allow editing that doctor's hours.
     */
    public function edit($doctorId = null)
    {
        if ($doctorId) {
            // allow only admins to edit someone else's hours
            if (!auth()->user()->hasRole('admin')) {
                abort(403);
            }
            $hours = WorkingHour::where('doctor_id', $doctorId)->orderBy('weekday')->orderBy('start_time')->get();
            $doctor = \App\Models\Doctor::findOrFail($doctorId);
            return view('doctor.working_hours.edit_for_admin', compact('hours','doctor'));
        }

        $doctor = Auth::user()->doctor;
        $hours = WorkingHour::where('doctor_id', $doctor->id)->orderBy('weekday')->orderBy('start_time')->get();
        return view('doctor.working_hours.index', compact('hours'));
    }

    public function store(Request $request, $doctorId = null)
    {
        // determine target doctor: admin may pass doctor id
        if ($doctorId) {
            if (!auth()->user()->hasRole('admin')) {
                abort(403);
            }
            $doctor = \App\Models\Doctor::findOrFail($doctorId);
        } else {
            $doctor = Auth::user()->doctor;
        }
        $data = $request->validate([
            'intervals' => 'required|array',
            'intervals.*.weekday' => 'required|integer|between:0,6',
            'intervals.*.start_time' => 'required|date_format:H:i',
            'intervals.*.end_time' => 'required|date_format:H:i|after:intervals.*.start_time',
        ]);

        // Validate overlaps per weekday
        $byDay = [];
        foreach($data['intervals'] as $int){
            $w = $int['weekday'];
            $s = $int['start_time'];
            $e = $int['end_time'];
            if(!isset($byDay[$w])) $byDay[$w]=[];
            foreach($byDay[$w] as $existing){
                // existing (es,ee), check overlap
                if(!($e <= $existing[0] || $s >= $existing[1])){
                    return back()->with('error', 'فترات متداخلة في اليوم: '.$w);
                }
            }
            $byDay[$w][] = [$s,$e];
        }

        DB::transaction(function() use ($doctor, $data){
            // replace existing
            WorkingHour::where('doctor_id', $doctor->id)->delete();
            foreach($data['intervals'] as $int){
                WorkingHour::create([
                    'doctor_id' => $doctor->id,
                    'weekday' => $int['weekday'],
                    'start_time' => $int['start_time'],
                    'end_time' => $int['end_time'],
                ]);
            }
        });

        if (auth()->user()->hasRole('admin') && $doctorId) {
            return redirect()->route('admin.doctors.show', $doctor->id)->with('success','تم حفظ ساعات العمل');
        }

        return redirect()->route('doctor.working_hours.index')->with('success','تم حفظ ساعات العمل');
    }

    public function reset()
    {
        $doctor = Auth::user()->doctor;
        // default: Mon-Fri 09:00-17:00
        WorkingHour::where('doctor_id', $doctor->id)->delete();
        for($d=1;$d<=5;$d++){
            WorkingHour::create(['doctor_id'=>$doctor->id,'weekday'=>$d,'start_time'=>'09:00','end_time'=>'17:00']);
        }
        return redirect()->route('doctor.working_hours.index')->with('success','تم إعادة الضبط');
    }
}
