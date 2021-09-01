<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

abstract class Controller extends BaseController
{
    protected $class;

    public function index(Request $request)
    {
        return $this->class::all();
    }

    public function store(Request $request)
    {
        return response()->json($this->class::create($request->all()), 201);
    }

    public function show(int $id)
    {
        $resource = $this->class::find($id);

        if(is_null($resource))
            return response()->json('No content.', 204);

        return response()->json($resource, 200);
    }

    public function update(int $id, Request $request)
    {
        $resource = $this->class::find($id);

        if(is_null($resource))
            return response()->json('Resource does not exist', 404);

        $resource->fill($request->all());
        $resource->save();

        return response()->json($resource, 200);
    }

    public function destroy(int $id)
    {
        $destroyed = $this->class::destroy($id);

        if($destroyed === 0)
            return response()->json('Resource does not exist', 404);

        return response()->json('Destroyed', 204);
    }
}
