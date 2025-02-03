<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterValidation;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Repositories\CrudRepository;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    protected $crudRepository;
    protected $files = ['profile_picture'];

    protected $folder = 'users';

    public function __construct(CrudRepository $crudRepository)
    {
        $this->crudRepository = $crudRepository;
    }

    // User registration
    public function register(RegisterValidation $request)
    {
        $data = $request->validated();
        $data['role'] = 'user';
        $this->crudRepository->store(new User, $data, $request, $this->folder, $this->files, [], ['password' => $data['password']]);
        $user = User::where('email', $data['email'])->first();

        $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);
        $user['profile_picture'] = $this->FilePath($this->folder, $user->id, $user->profile_picture);
        return response()->json(compact('user', 'token'), 201);
    }

    // User login

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $user = auth()->user();
            $token = JWTAuth::claims([
                'role' => $user->role,
                'email' => $user->email
            ])->fromUser($user);

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ]
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    // Get authenticated user
    public function getUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 400);
        }
        $user['profile_picture'] = $this->ShowFileURl($this->files, 'users', $user->id, $user->toArray());
        return response()->json(compact('user'));
    }

    // User logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }
    private function FilePath($folder, $id, $file)
    {
        return Storage::url($folder . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $file);
    }
    private function ShowFileURl($files, $folder, $id, $query)
    {

        if (!empty($files) && !empty($folder)) {
            foreach ($files as $key) {
                $query[$key] = $query[$key] == null ? null : $this->FilePath($folder, $id, $query[$key] ?? null);
            }
        }
        return $query;
    }
   

}
