<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Jobs\SendMails;
use App\Mail\TaskCompleted;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
