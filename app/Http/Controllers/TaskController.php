<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * TaskController constructor.
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * @return mixed
     */
    public function index()
    {
        // $tasks = $this->user->tasks()->get(['id', 'title', 'content', 'attachment', 'done_at'])->toArray();
        $tasks = $this->user->tasks()->get()->toArray();

        return $tasks;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $task = $this->user->tasks()->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
            ], 400);
        }

        return $task;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'attachment' => 'required',
        ]);

        $task = new Task();
        $task->title = $request->title;
        $task->content = $request->content;
        $task->attachment = $request->attachment;

        if ($this->user->tasks()->save($task))
            return response()->json([
                'success' => true,
                'task' => $task
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task could not be added.'
            ], 500);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $task = $this->user->tasks()->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
            ], 400);
        }

        // $updated = $task->fill($request->all())->save();
        if ($request->input('done_at') != null){
            $updated = $task->update($request->only('title', 'content', 'attachment', 'done_at'));
        } else {
            $updated = $task->update($request->only('title', 'content', 'attachment'));
        }

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task could not be updated.'
            ], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $task = $this->user->tasks()->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
            ], 400);
        }

        if ($task->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Task could not be deleted.'
            ], 500);
        }
    }

    /**
     * Destroy all tasks.
     *
     * @param  Request  $request
     * @return Response
     */
    public function destroyall()
    {
        // foreach ($this->tasks->forUser($request->user()) as $task) {
        //     $this->authorize('destroy', $task);
        //     $task->delete();
        // }

        // return redirect('/tasks');
        $tasks = $this->user->tasks()->get();
        foreach ($tasks as $task) {
            if (!$task->delete()){
                return response()->json([
                    'success' => false,
                    'message' => 'Task could not be deleted.'
                ], 500);
            }
        }
        return response()->json([
                'success' => true
            ]);
    }
}
