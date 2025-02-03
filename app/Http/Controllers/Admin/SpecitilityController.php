<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Speciality\StoreSpecialityRequest;
use App\Http\Requests\Admin\Speciality\UpdateSpecialityRequest;
use App\Interfaces\CrudRepositoryInterface;
use App\Models\Specality;
use Illuminate\Http\Request;

class SpecitilityController extends Controller
{
    protected $interface;
    protected $model;
    protected $files = [];

    protected $folder = null;

    protected  $panel = "Specality";

    public function __construct(CrudRepositoryInterface $interface, Specality $model)
    {
        $this->interface = $interface;
        $this->model = $model;
    }

    // List all products
    public function index(Request $request)
    {
        $where = $request->has('where') ? $request->get('where')->toArray() : [];
        $whereNot = $request->has('whereNot') ? $request->get('whereNot')->toArray() : [];
        $search = $request->has('search') ? $request->get('search')->toArray() : [];
        $active = $request->has('active') ? $request->get('active') : false;
        $verify = $request->has('verify') ? $request->get('verify') : false;
        $paginated = $request->get('paginated', true); // Default to paginated results
        $data =  $this->interface->index($this->model, $paginated, $this->folder, $this->files,  $where, $whereNot, $search, $active, $verify);
        return response()->json($data, 200);
    }

    // Show a single product
    public function show($id, Request $request)
    {
        $where = $request->has('where') ? $request->get('where')->toArray() : [];
        $whereNot = $request->has('whereNot') ? $request->get('whereNot')->toArray() : [];
        $search = $request->has('search') ? $request->get('search')->toArray() : [];
        $active = $request->has('active') ? $request->get('active')->toArray() : null;
        $verify = $request->has('verify') ? $request->get('verify')->toArray() : null;
        $data =  $this->interface->getById($this->model, $id, $this->folder, $this->files, $where, $whereNot, $search, $active, $verify);
        return response()->json($data, 200);
    }

    // Create a new product
    public function store(StoreSpecialityRequest $request)
    {

        $data = $request->validated();

        $modifiedValues = ['price' => (float) $data['price']]; // Example modification
        $hashingValues = [];

        // Example: keys to hash (none in this case) not need to include 
        $data =  $this->interface->store($this->model, $data, $request, $this->folder, $this->files,  $modifiedValues, $hashingValues);
        return response()->json($data, 201);
    }

    // Update an existing product
    public function update(UpdateSpecialityRequest $request, $id)
    {
        $data = $request->validated();
        $modifiedValues = ['price' => (float) $data['price']]; // Example modification
        $hashingValues = []; // Example: keys to hash (none in this case)

        $updated =  $this->interface->update(
            $this->model,
            $data,
            $id,
            $request, // Where conditions
            $this->folder,
            $this->files, // WhereNot conditions
            $modifiedValues,
            $hashingValues,
            // Search

        );

        return response()->json(['success' => $updated], 200);
    }

    // Delete a product
    public function destroy(Request $request, $id)
    {

        $this->interface->delete($this->model, $this->folder, $id);
        return response()->json(['message' => $this->panel . ' deleted successfully'], 200);
    }
}
