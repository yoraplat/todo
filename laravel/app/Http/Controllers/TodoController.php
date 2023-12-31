<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Jobs\SendMails;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = QueryBuilder::for(Todo::class)
            ->allowedFilters(['search'])
            ->where('user_id', Auth::id())
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->with('user')
            ->get();

        return TodoResource::collection($todos);
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
        if (Auth::id() != $request->user_id) {
            return response()->json([
                'message' => 'Todo does not belong to this user',
                'data' => $todo,
            ], 403);
        }

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
