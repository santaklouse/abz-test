<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Image;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\GetUserRequest;
use App\Http\Requests\User\GetUsersRequest;
use App\Http\Requests\User\SaveUserRequest;
use App\Http\Resources\UserApiResource;
use App\Http\Resources\UsersApiPagination;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class UsersController extends Controller
{

    public function index(GetUsersRequest $request)
    {
        try {
            $users = User::paginate($request->query('count', 6));
            $users->setPath(URL::current());
            return new UsersApiPagination($users);
        }
        catch (\Throwable $throwable) {
            return $this->jsonResponse('Page not found.', 404);
        }
    }

    public function store(SaveUserRequest $request)
    {
        $path = $request
            ->file('photo')
            ->storePublicly(User::PHOTO_PATH, 'public');
        Image::cropAndOptimize($path);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'position_id' => $request->position_id,
                'photo' => $path,
            ]);
        } catch (Exception $e) {
            Storage::disk('public')->delete($path);
            throw $e;
        }

        return $this->jsonResponse(
            "New user successfully registered",
            201,
            ["user_id" => $user->id]
        );
    }

    public function show(GetUserRequest $request, string $id)
    {
        try {
            return new UserApiResource(
                User::findOrFail($id)
            );
        } catch (\Throwable $throwable) {
            return $this->jsonResponse('User not found.', 404);
        }
    }

}
