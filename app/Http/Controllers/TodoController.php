<?php

namespace App\Http\Controllers;

use App\Models\todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return todo::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'title' => 'required',
            'isCompleted' => ''
        ]);

        if (!$request->isComplete) {
            $input['isCompleted'] = false;
        }

        $todo = new todo;
        $todo->title = $input['title'];
        $todo->isComplete = $input['isCompleted'];
        $todo->save();
        return response()->json($todo);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\todo $todo
     * @return \Illuminate\Http\Response
     */
    public function show(todo $todo)
    {
        return $todo;
    }


    public function allDelete(Request $request)
    {
        todo::whereIn('id', $request->all())->delete();
    }

    public function allUpdate(Request $request)
    {

        foreach ($request->all() as $todo) {
            todo::whereId($todo->id)->update(["isComplete"=>$todo["isCompleted"], "title" => $todo["title"]]);
        }
        return response()->json("", 200);
    }

    public function allStore(Request $request)

    {

        foreach ($request->all() as $todo) {
            todo::create(["isComplete"=>$todo["isCompleted"], "title" => $todo["title"]]);


        }
        return response()->json("", 200);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\todo $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, todo $todo)
    {
        $input = $request->validate([
            'title' => 'required',
            'isCompleted' => ''
        ]);
        $todo->title = $input['title'];
        $todo->isComplete = $input['isCompleted'];
        $todo->save();

        Log::debug($todo);
        return response()->json($todo);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\todo $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(todo $todo)
    {
        if($todo){
            $todo->delete();
            return response()->json("", 200);
        }
        return response()->json("", 400);
    }
}
