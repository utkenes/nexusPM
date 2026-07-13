<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();

        return view('dashboard', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max::255',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'Beklemede',
        ]);

        return redirect()->back();
    }
}
