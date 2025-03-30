<?php

namespace App\Http\Controllers;

use App\Helpers\Image;
use App\Http\Requests\User\GetUserRequest;
use App\Http\Requests\User\GetUsersRequest;
use App\Http\Requests\User\SaveUserRequest;
use App\Http\Resources\UserApiResource;
use App\Http\Resources\UsersApiPagination;
use App\Models\User;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Exception;

class UserController extends Controller
{

/*
i need page for testing api
 - show users list with pagination
 - create user
 - show user
 - get token for registration
 - show list of positions

please create this page using bootstrap + vuejs (components) and laravel
page should be responsive and look good on mobile.

POST /users
    in header - Token for registration

    All form fields are required:

    name - user name, should be 2-60 characters
    email - user email, must be a valid email according to RFC2822
    phone - user phone number, should start with code of Ukraine +380
    position_id - user position ID. You can get list of all positions with their IDs using the API method GET api/v1/positions
    photo - user photo should be jpg/jpeg image, with resolution at least 70x70px and size must not exceed 5MB.
Response:
{
  "success": true,
  "user_id": 23,
  "message": "New user successfully registered"
}
Errors:
{
  "success": false,
  "message": "Validation failed",
  "fails": {
    "name": [
      "The name must be at least 2 characters."
    ],
    "email": [
      "The email must be a valid email address."
    ],
    "phone": [
      "The phone field is required."
    ],
    "position_id": [
      "The position id must be an integer."
    ],
    "photo": [
      "The photo may not be greater than 5 Mbytes."
    ]
  }
}

{
  "success": false,
  "message": "The token expired."
}
GET /users
    in query - count - number of users per page, default 6
                page - page number, default 1

    Response:
    {
      "success": true,
      "page": 1,
      "total_pages": 10,
      "total_users": 47,
      "count": 5,
      "links": {
        "next_url": "https://frontend-test-assignment-api.abz.agency/api/v1/users?page=2&count=5",
        "prev_url": null
      },
      "users": [
        {
          "id": "30",
          "name": "Angel",
          "email": "angel.williams@example.com",
          "phone": "+380496540023",
          "position": "Designer",
          "position_id": "4",
          "registration_timestamp": 1537777441,
          "photo": "https://frontend-test-assignment-api.abz.agency/images/users/5b977ba13fb3330.jpeg"
        },
       ]
    }
GET /users/{id}
Response:
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Superstar",
    "email": "Superstar@gmail.com",
    "phone": "+380957398462",
    "position": "Security",
    "position_id": 2,
    "photo": "https://frontend-test-assignment-api.abz.agency/images/users/5b9626f0157d224.jpeg"
  }
}
GET /positions
Response:
{
  "success": true,
  "positions": [
    {
      "id": 1,
      "name": "Manager"
    },
    {
      "id": 2,
      "name": "Security"
    },
    {
      "id": 3,
      "name": "Designer"
    },
    {
      "id": 4,
      "name": "Developer"
    }
  ]
}
GET /token

Response:
{
  "success": true,
  "token": "eyJpdiI6Im9mV1NTMlFZQTlJeWlLQ3liVks1MGc9PSIsInZhbHVlIjoiRTJBbUR4dHp1dWJ3ekQ4bG85WVZya3ZpRGlMQ0g5ZHk4M05UNUY4Rmd3eFM3czc2UDRBR0E4SDR5WXlVTG5DUDdSRTJTMU1KQ2lUQmVZYXZZOHJJUVE9PSIsIm1hYyI6ImE5YmNiODljZjMzMTdmMDc4NjEwN2RjZTVkNzBmMWI0ZDQyN2YzODI5YjQxMzE4MWY0MmY0ZTQ1OGY4NTkyNWQifQ=="
}
 */
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
