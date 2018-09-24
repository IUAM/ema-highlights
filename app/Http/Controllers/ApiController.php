<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index($resource)
    {
        $model = $this->getModelFromResource($resource);

        return $model::paginate(12);
    }

    public function show($resource, $id)
    {
        $model = $this->getModelFromResource($resource);

        return $model::findOrFail($id);
    }

    private function getModelFromResource($resource)
    {
        $model = ucfirst(str_singular($resource));

        return '\App\Models\\' . $model;
    }

    private function validateResource($resource)
    {
        // TODO: Throw not found exception here?
    }
}
