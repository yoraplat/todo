<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Jobs\SendMails;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = User::find(Auth::id());
        return TodoResource::collection($todos->todos()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request)
    {
        $todo = new Todo($request->validated());
        $todo->save();

        return new TodoResource($todo);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        $todo->update($request->validated());

        if ($todo->is_completed) {
            SendMails::dispatchAfterResponse($todo);
        }

        return response()->json([
            'message' => 'Todo updated successfully',
            'data' => $todo
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        if ($todo->user_id == Auth::id()) {
            $todo->delete();
        }

        return response()->json([
            'message' => 'Todo deleted successfully',
        ], 204);
    }


    /**
     * Display the specified resources by userId.
     */
    public function getByUserId(User $user)
    {
      return TodoResource::collection($user->todos()->get());
    }
}
