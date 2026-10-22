<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private function checkOwner(Task $task)
    {
        abort_unless($task->user_id === auth()->id(), 403);
    }

    public function index(Request $request)
    {
        $tasks = Task::where('user_id', auth()->id())
            ->when($request->boolean('pending'),
                fn($q) => $q->pending())
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'required|in:rendah,sedang,tinggi',
        ]);

        $data['user_id'] = auth()->id();
        Task::create($data);

        return redirect()->route('tasks.index');
    }

    public function show(Task $task)
    {
        $this->checkOwner($task);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->checkOwner($task);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $this->checkOwner($task);

        $data = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'required|in:rendah,sedang,tinggi',
            'is_done' => 'required|boolean',
        ]);

        $task->update($data);

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $this->checkOwner($task);
        $task->delete();

        return redirect()->route('tasks.index');
    }

    public function toggle(Task $task)
    {
        $this->checkOwner($task);
        $task->update(['is_done' => !$task->is_done]);

        return redirect()->route('tasks.index');
    }
}