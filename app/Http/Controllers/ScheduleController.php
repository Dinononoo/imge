<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function processSchedules(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $images = [];
        foreach ($request->file('images') as $file) {
            $filename = uniqid() . '-' . $file->getClientOriginalName();
            $filePath = public_path('uploads/' . $filename);
            $file->move(public_path('uploads'), $filename);
            $images[] = $filePath;
        }

        $schedules = [];
        foreach ($images as $imagePath) {
            $schedules[] = $this->callPythonScript($imagePath);
        }

        $commonFreeTime = $this->mergeSchedules($schedules);
        return view('schedules', ['data' => $commonFreeTime]);
    }

    private function callPythonScript($imagePath)
    {
        $pythonPath = 'D:\\python\\python.exe'; // กำหนด path ของ Python ตรงนี้
        $process = new Process([
            $pythonPath,
            base_path('scripts/analyze_image_with_color.py'),
            $imagePath,
        ]);
        $process->run();
    
        if (!$process->isSuccessful()) {
            Log::error("Error running Python script: " . $process->getErrorOutput());
            return [];
        }
    
        $output = $process->getOutput();
        return json_decode($output, true) ?? [];
    }
    

    private function mergeSchedules(array $schedules)
    {
        if (empty($schedules)) {
            return [];
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $timeSlots = [
            "08:00-09:00", "09:00-10:00", "10:00-11:00", "11:00-12:00",
            "12:00-13:00", "13:00-14:00", "14:00-15:00", "15:00-16:00",
            "16:00-17:00", "17:00-18:00"
        ];

        $merged = array_fill_keys($days, array_fill_keys($timeSlots, 'ว่าง'));

        foreach ($schedules as $schedule) {
            foreach ($days as $day) {
                foreach ($timeSlots as $time) {
                    if (isset($schedule[$day][$time]) && $schedule[$day][$time] === 'ไม่ว่าง') {
                        $merged[$day][$time] = 'ไม่ว่าง';
                    }
                }
            }
        }

        return $merged;
    }
}
