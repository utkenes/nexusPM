<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Display a monthly calendar view mapping tasks to their due dates.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $organization = $user->currentOrganization;

        if (! $organization) {
            return redirect()->route('organizations.index')
                ->with('warning', 'Please select or create an organization first.');
        }

        $projects = $organization->projects()->get();
        $projectIds = $projects->pluck('id');

        $month = (int) $request->input('month', date('n'));
        $year = (int) $request->input('year', date('Y'));

        // Handle boundaries
        if ($month < 1) {
            $month = 12;
            $year--;
        } elseif ($month > 12) {
            $month = 1;
            $year++;
        }

        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $daysInMonth = $startOfMonth->daysInMonth;
        $startOfWeekDay = $startOfMonth->dayOfWeekIso; // 1 = Monday, 7 = Sunday
        $endOfWeekDay = $endOfMonth->dayOfWeekIso;

        // Fetch all tasks with due dates in this month
        $tasks = Task::whereIn('project_id', $projectIds)
            ->whereNotNull('due_date')
            ->whereYear('due_date', $year)
            ->whereMonth('due_date', $month)
            ->with(['project', 'assignee'])
            ->get();

        // Group tasks by day of month (YYYY-MM-DD)
        $tasksByDay = $tasks->groupBy(function ($task) {
            return $task->due_date->format('Y-m-d');
        });

        // Navigation links
        $prevMonth = $month - 1;
        $prevYear = $year;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear--;
        }

        $nextMonth = $month + 1;
        $nextYear = $year;
        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear++;
        }

        $currentMonthName = $startOfMonth->format('F Y');

        return view('calendar.index', compact(
            'organization',
            'month',
            'year',
            'daysInMonth',
            'startOfWeekDay',
            'endOfWeekDay',
            'tasksByDay',
            'currentMonthName',
            'prevMonth',
            'prevYear',
            'nextMonth',
            'nextYear'
        ));
    }
}
