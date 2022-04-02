<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::paginate();
        $taskStatuses = DB::table('task_statuses')->get();
        $users = DB::table('users')->get();
        return view('tasks.index', compact('tasks', 'taskStatuses', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task();
        $taskStatuses = DB::table('task_statuses')->get();
        $users = DB::table('users')->get();
        $labels = DB::table('labels')->get();
        return view('tasks.create', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|unique:tasks|max:255',
            'description' => 'max:1000',
            'status_id' => 'required'
        ]);
        $task = new Task();
        $task->fill($data);
        $task->created_by_id = Auth::id();
        $labels = $request->input()['labels'];
        if (!$labels[0]) {
            unset($labels[0]);
        } else {
            $task->labels()->sync($labels);
        }
        $task->save();
        flash(__('messages.taskSuccessAdded'))->success();
        return redirect(route('tasks.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $taskStatuses = DB::table('task_statuses')->get();
        $users = DB::table('users')->get();
        $labels = DB::table('labels')->get();
        return view('tasks.edit', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $data = $this->validate($request, [
            'name' => 'required:tasks|max:255',
            'description' => 'max:1000',
            'status_id' => 'required'
        ]);
        $task->fill($data);
        $task->created_by_id = Auth::id();
        $labels = $request->labels;
        if (isset($labels) && $labels[0] === null) {
            $labels = [];
        }
        $task->labels()->sync($labels);
        $task->save();
        flash(__('flash.tasks.update.success'))->success();
        return redirect(route('tasks.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $userIdAuth = Auth::id();
        $userOwnerId = $task->createdBy->id;
        if ($userIdAuth === $userOwnerId) {
            $task->labels()->detach();
            $task->delete();
            flash(__('flash.tasks.delete.success'))->success();
        } else {
            flash(__('flash.tasks.failed_to_delete.error'))->error();
        }
        return redirect()->route('tasks.index');
    }
}
