<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleSetting;
use App\Models\TaskExecutionLog;
use Illuminate\Support\Facades\Artisan;

class TaskDashboardController extends Controller
{
    public function index()
    {
        $settings = ScheduleSetting::all()->keyBy('command_signature');
        $logs = TaskExecutionLog::orderBy('executed_at', 'desc')->take(10)->get();
        return view('task_dashboard', compact('settings', 'logs'));
    }

    public function runCommand(Request $request)
    {
        $request->validate([
            'command' => 'required|string'
        ]);

        $command = $request->command;
        
        try {
            Artisan::call($command);
            $output = Artisan::output();
            return response()->json([
                'success' => true,
                'message' => 'Command executed successfully.',
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateInterval(Request $request)
    {
        $request->validate([
            'command' => 'required|string',
            'interval' => 'required|string'
        ]);

        $setting = ScheduleSetting::where('command_signature', $request->command)->firstOrFail();
        $setting->update([
            'interval_type' => $request->interval
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule interval updated successfully.'
        ]);
    }

    public function getChartData()
    {
        $healthLogs = TaskExecutionLog::where('command_signature', 'system:health')
            ->orderBy('executed_at', 'desc')
            ->take(15)
            ->get()
            ->reverse();

        return response()->json([
            'labels' => $healthLogs->map(fn($log) => $log->executed_at->format('H:i:s'))->values(),
            'data' => $healthLogs->map(fn($log) => $log->memory_used)->values()
        ]);
    }
}