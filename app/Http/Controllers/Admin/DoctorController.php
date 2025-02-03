<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Doctor\StoreDoctorRequest;
use App\Http\Requests\Admin\Doctor\UpdateDoctorRequest;
use App\Interfaces\CrudRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    protected $interface;
    protected $model;
    protected $files = ['profile_picture'];

    protected $folder = 'users';

    protected  $panel = "Doctor";

    public function __construct(CrudRepositoryInterface $interface, User $model)
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
        $paginated = $request->get('paginated', true);
        $relation = ['doctorAvailable.availableTimes'];
        $data =  $this->interface->index($this->model, $paginated, $this->folder, $this->files,  $where, $whereNot, $search, $active, $verify, $relation);
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
    public function store(StoreDoctorRequest $request)
    {
        $data = $request->validated();
        $availability = $data['availability'] ?? [];
        unset($data['availability']); // Remove from main data

        $modifiedValues = [];
        $hashingValues = ['password'];

        // Create relations array for the repository
        $relations = [];
        if (!empty($availability)) {
            $relations['doctorAvailable'] = [];
            foreach ($availability as $dateData) {
                $availableDate = [
                    'date' => $dateData['date'],
                    'availableTimes' => []
                ];

                foreach ($dateData['times'] as $time) {
                    $availableDate['availableTimes'][] = [
                        'time' => $time
                    ];
                }
                $relations['doctorAvailable'][] = $availableDate;
            }
        }

        $data = $this->interface->store(
            $this->model,
            $data,
            $request,
            $this->folder,
            $this->files,
            $modifiedValues,
            $hashingValues,
            $relations
        );

        return response()->json($data, 201);
    }

    // Update an existing product
    public function update(UpdateDoctorRequest $request, $id)
    {
        $data = $request->validated();
        $availability = $data['availability'] ?? [];
        unset($data['availability']);

        $modifiedValues = [];
        $hashingValues = ['password'];

        // Format relations for update
        $relations = [];
        if (!empty($availability)) {
            $relations['doctorAvailable'] = [
                'delete' => true, // Delete existing records
                'data' => []
            ];

            foreach ($availability as $dateData) {
                $availableDate = [
                    'date' => $dateData['date'],
                    'availableTimes' => []
                ];

                foreach ($dateData['times'] as $time) {
                    $availableDate['availableTimes'][] = [
                        'time' => $time
                    ];
                }
                $relations['doctorAvailable']['data'][] = $availableDate;
            }
        }

        $data = $this->interface->update(
            $this->model,
            $data,
            $id,
            $request,
            $this->folder,
            $this->files,
            $modifiedValues,
            $hashingValues,
            [], // where
            [], // whereNot
            [], // search
            null, // active
            null, // verify
            $relations
        );

        return response()->json($data, 200);
    }

    // Delete a product
    public function destroy(Request $request, $id)
    {

        $this->interface->delete($this->model, $this->folder, $id);
        return response()->json(['message' => $this->panel . ' deleted successfully'], 200);
    }
}
