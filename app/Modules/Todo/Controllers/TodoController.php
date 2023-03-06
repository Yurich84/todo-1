<?php

namespace App\Modules\Todo\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Modules\Todo\Requests\TodoRequest;
use App\Modules\Todo\Resources\TodoResource;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TodoController extends Controller
{
    /**
     * Display list of resources
     *
     * @param  Request  $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        [$column, $order] = explode(',', $request->input('sortBy', 'id,asc'));
        $pageSize = (int) $request->input('pageSize', 10);

        $resource = Todo::query()
            ->when($request->filled('search'), function (Builder $q) use ($request) {
                $q->where(Todo::COLUMN_NAME, 'like', '%'.$request->search.'%');
            })
            ->orderBy($column, $order)->paginate($pageSize);

        return TodoResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TodoRequest  $request
     * @param  Todo  $todo
     * @return JsonResponse
     */
    public function store(TodoRequest $request, Todo $todo)
    {
        $data = $request->validated();
        $todo->fill($data)->save();

        if (Todo::whereDone(false)->count() > 2) {
            Todo::oldest()->whereDone(false)->first()->forceFill(['done' => true])->save();
        }

        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully created',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Todo  $todo
     * @return TodoResource
     */
    public function show(Todo $todo)
    {
        return new TodoResource($todo);
    }

    /**
     * Display the specified resource.
     *
     * @param  Todo  $todo
     */
    public function done(Todo $todo)
    {
        $todo->forceFill(['done' => !$todo->done])->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TodoRequest  $request
     * @param  Todo  $todo
     * @return JsonResponse
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        $data = $request->validated();
        $todo->fill($data)->save();

        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated',
        ]);
    }

    /**
     * Delete the specified resource.
     *
     * @param  Todo  $todo
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully deleted',
        ]);
    }
}
