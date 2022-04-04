<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('assigned_to_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('status_id'),
            ])->paginate(10);
        $taskStatuses = DB::table('task_statuses')->get();
        $users = DB::table('users')->get();
        $activeFilters = optional(request()->get('filter'));
        return view('tasks.index', compact('tasks', 'taskStatuses', 'users', 'activeFilters'));
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
            'name' => 'required|unique:tasks',
            'description' => 'nullable',
            'status_id' => 'required',
            'assigned_to_id' => 'nullable',
        ], [
            'unique' => __('messages.flash.validation.taskUnique'),
        ]);
        $task = new Task();
        $task->fill($data);
        $task->created_by_id = Auth::id();
        $task->save();
        $labels = $request->labels;
        if (is_array($labels) && $labels[0] !== null) {
            $task->labels()->sync($labels);
        }
        flash(__('messages.flash.success.added', ['subject' => __('task.subject')]))->success();
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
            'name' => 'required:tasks',
            'description' => 'nullable',
            'status_id' => 'required',
            'assigned_to_id' => 'nullable',
        ]);
        $task->fill($data);
        $task->created_by_id = Auth::id();
        $task->save();
        $labels = $request->labels;
        if ($labels[0] === null) {
            $labels = [];
        }
        $task->labels()->sync($labels);

        flash(__('messages.flash.success.updated', ['subject' => __('task.subject')]))->success();
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
        $task->labels()->detach();
        $task->delete();
        flash(__('messages.flash.success.deleted', ['subject' => __('task.subject')]))->success();
        return redirect()->route('tasks.index');
    }
}
