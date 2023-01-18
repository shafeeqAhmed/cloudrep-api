<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Resources\ClientProfileItemResource;
use App\Models\Campaign;
use App\Models\ClientProfileItem;
use App\Models\Gamification;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Models\Wallet;

class UserController extends ApiController
{
    /**
     * @OA\Get(
     * path="/api/user",
     * summary="Get Users",
     * description="Get Users",
     * operationId="Get Users",
     * tags={"User"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort user by name param",
     *    in="query",
     *    name="name",
     *    example="test user",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort user by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort user by pagination",
     *    in="query",
     *    name="name",
     *    example="1",
     *    @OA\Schema(
     *       type="integer"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User has been Fetched Successfully!',
     *       'data': {
     *          'id': 2,
     *          'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *          'name': 'John Doe',
     *          'first_name': 'John',
     *          'last_name': Doe',
     *          'email': 'johndoe@gmail.com',
     *          'phone_no': '+11236547891',
     *          'role': null,
     *          'profile_photo': {},
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User Not Found")
     *        )
     *     ),
     * )
     */
    public function getAllUsers(Request $request)
    {
        $users = User::getUsers($request);
        if (empty($users)) {
            return $this->respond([
                'status' => false,
                'message' => 'User Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Users has been Fetched  Successfully!',
            'data' => [
                'users' => $users
            ],
        ]);
    }


    /**
     * @OA\Get(
     * path="/api/user/{user_uuid}",
     * summary="Get User By User uuid",
     * description="Get User By user_uuid",
     * operationId="getUserById",
     * tags={"User"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="user_uuid of User",
     *    in="path",
     *    name="user_uuid",
     *    required=true,
     *    example="fb5700d7-d543-4d41-8192-f9d962517f46",
     *    @OA\Schema(
     *       type="string",
     *       format="int64"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User has been Fetched Successfully!',
     *       'data': {
     *          'id': 2,
     *          'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *          'name': 'John Doe',
     *          'first_name': 'John',
     *          'last_name': Doe',
     *          'email': 'johndoe@gmail.com',
     *          'phone_no': '+11236547891',
     *          'role': null,
     *          'profile_photo': {},
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User Not Found")
     *        )
     *     ),
     * )
     */
    public function show($user_uuid)
    {
        $user = User::where('user_uuid', $user_uuid)->first();
        if (empty($user)) {
            return $this->respond([
                'status' => false,
                'message' => 'User Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'User has been Fetched Successfully!',
            'data' => new UserResource($user)
        ]);
    }
    /**
     * @OA\Get(
     * path="/api/users/{role}",
     * summary="Get User By Role",
     * description="Get User By role",
     * operationId="getUserByRole",
     * tags={"User"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="Role of User",
     *    in="path",
     *    name="role",
     *    required=true,
     *    example="agent,admin,client",
     *    @OA\Schema(
     *       type="string",
     *       format="int64"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User has been Fetched Successfully!',
     *       'data': {
     *          'id': 2,
     *          'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *          'name': 'John Doe',
     *          'first_name': 'John',
     *          'last_name': Doe',
     *          'email': 'johndoe@gmail.com',
     *          'phone_no': '+11236547891',
     *          'role': null,
     *          'profile_photo': {},
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User Not Found")
     *        )
     *     ),
     * )
     */
    public function getUsersByRole($role, Request $request)
    {
        $users = User::whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->q, function ($query, $q) {
                return $query->where('name', 'LIKE', "%{$q}%")->orWhere('email', 'LIKE', "%{$q}%");
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->orderBy('id', 'desc')
            ->paginate($request->perPage);

        if (empty($users)) {
            return $this->respond([
                'status' => false,
                'message' => 'User Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'User has been Fetched Successfully!',
            'data' => $users
        ]);
    }
    /**
     * @OA\Delete(
     * path="/api/user/user_uuid",
     * summary="Delete existing User",
     * description="Delete User by setting_uuid",
     * operationId="deleteUser",
     * tags={"User"},
     * @OA\Parameter(
     *    description="user_uuid of User",
     *    in="path",
     *    name="user_uuid",
     *    required=true,
     *    example="53adb8de-3cab-4aec-9db2-5bc7fd40b764",
     *    @OA\Schema(
     *       type="string",
     *       format="int64"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User has been Deleted Successfully!',
     *       'data': {
     *          'id': 2,
     *          'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *          'name': 'John Doe',
     *          'first_name': 'John',
     *          'last_name': Doe',
     *          'email': 'johndoe@gmail.com',
     *          'phone_no': '+11236547891',
     *          'role': null,
     *          'profile_photo': {},
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User Not Found")
     *        )
     *     ),
     * )
     */
    public function destroy($user_uuid)
    {
        $user = User::where('user_uuid', $user_uuid)->first();
        if (empty($user)) {
            return $this->respond([
                'status' => false,
                'message' => 'User Not Found',
                'data' =>  []
            ]);
        }
        $user->delete();
        return $this->respond([
            'status' => true,
            'message' => 'User has been Deleted Successfully!',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * @OA\Put(
     * path="/api/user/{user_uuid}",
     * summary="update User",
     * description="Update User",
     * operationId="updateUser",
     * tags={"User"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user data",
     *    @OA\JsonContent(
     *       required={"first_name","last_name","email"},
     *       @OA\Property(property="first_name", type="string", format="first_name", example="john"),
     *       @OA\Property(property="last_name", type="string", format="last_name", example="doe"),
     *       @OA\Property(property="email", type="string", format="email", example="test@gmail.com"),
     *       @OA\Property(property="photo", type="string", format="photo", example="uploads/users/1654705018327223585jpg"),
     *    ),
     * ),
     * * @OA\Parameter(
     *    description="user_uuid of User",
     *    in="path",
     *    name="user_uuid",
     *    required=true,
     *    example="53adb8de-3cab-4aec-9db2-5bc7fd40b764",
     *    @OA\Schema(
     *       type="string",
     *       format="int64"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User has been updated Successfully!',
     *       'data': {
     *          'id': 2,
     *          'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *          'name': 'John Doe',
     *          'first_name': 'John',
     *          'last_name': Doe',
     *          'email': 'johndoe@gmail.com',
     *          'phone_no': '+11236547891',
     *          'role': null,
     *          'profile_photo': {},
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User Not Found")
     *        )
     *     ),
     * )
     */
    public function update($user_uuid, UserRequest $request)
    {
        $user = User::getRecord('user_uuid', $user_uuid);
        $data = $request->validated();
        $data['name'] = $request->first_name . ' ' . $request->last_name;
        if (
            $data['email'] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $data);
        } else {
            $user->forceFill([
                'name' => $data['name'],
                'email' => $data['email'],
            ])->save();
        }
        if (isset($data['profile_photo_path'])) {
            $data['profile_photo_path'] = uploadImage('photo', 'uploads/users/', 300, 300);
        }
        $user->update($data);

        if (empty($user)) {
            return $this->respond([
                'status' => false,
                'message' => 'User Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'User has been Updated Successfully!',
            'data' => new UserResource($user)
        ]);
    }




    protected function updateVerifiedUser($user, array $data)
    {
        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }

    /**
     * @OA\Post(
     * path="/api/update-general-information",
     * summary="update General Information",
     * description="Update General Information",
     * operationId="updateGeneralInformation",
     * tags={"User"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user data",
     *    @OA\JsonContent(
     *       required={"first_name","last_name","jwtToken"},
     *       @OA\Property(property="first_name", type="string", format="first_name", example="john"),
     *       @OA\Property(property="last_name", type="string", format="last_name", example="doe"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User general information has been updated Successfully!',
     *       'data': {
     *          userData: {
     *              'id': 2,
     *              'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *              'name': 'John Doe',
     *              'first_name': 'John',
     *              'last_name': Doe',
     *              'email': 'johndoe@gmail.com',
     *              'phone_no': '+11236547891',
     *              'role': null,
     *              'bio': 'john doe',
     *              'birth_date': null,
     *              'country': 'canada',
     *              'website': 'cloudrepai.com',
     *              'twitter': null,
     *              'facebook': null,
     *              'google': null,
     *              'linkedin': null,
     *              'instagram': null,
     *              'quora': null,
     *              'profile_photo': {},
     *              'created_at': '2022-06-04T18:32:20.000000Z',
     *              'updated_at': '2022-06-04T18:36:16.000000Z',
     *              'deleted_at': null
     *          }
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User Not Found")
     *        )
     *     ),
     * )
     */
    public function updateGeneralInformation(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);


        User::where('id', $request->user()->id)->update($data);

        return $this->respond([
            'status' => true,
            'message' => 'User general information has been Updated Successfully!',
            'data' => [
                'userData' => User::getRecordById($request->user()->id)
            ]
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/upload-profile-image",
     * summary="Upload Profile Image",
     * description="Upload Profile Image",
     * operationId="uploadProfileImage",
     * tags={"User"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass profile image data",
     *    @OA\JsonContent(
     *       required={"image","jwtToken"},
     *       @OA\Property(property="image", type="string", format="image", example="abc.png"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Profile Image has been Updated Successfully!',
     *       'data': {
     *          userData: {
     *              'id': 2,
     *              'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *              'name': 'John Doe',
     *              'first_name': 'John',
     *              'last_name': Doe',
     *              'email': 'johndoe@gmail.com',
     *              'phone_no': '+11236547891',
     *              'role': null,
     *              'bio': 'john doe',
     *              'birth_date': null,
     *              'country': 'canada',
     *              'website': 'cloudrepai.com',
     *              'twitter': null,
     *              'facebook': null,
     *              'google': null,
     *              'linkedin': null,
     *              'instagram': null,
     *              'quora': null,
     *              'profile_photo': 'http://127.0.0.1:8000/storage/user/profile/SJDgCRdeoiG8isTkezKbyufF6rRaYb.png',
     *              'created_at': '2022-06-04T18:32:20.000000Z',
     *              'updated_at': '2022-06-04T18:36:16.000000Z',
     *              'deleted_at': null
     *          }
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Profile Image Not Found")
     *        )
     *     ),
     * )
     */
    public function uploadProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $user = User::where('id', $request->user()->id)->first();
        if ($user->profile_photo_path) {
            removeImage('user/profile', $user->profile_photo_path);
        }

        $image = uploadImage('profile_image', 'user/profile', 300, 300);
        $user->update(['profile_photo_path' => $image]);
        return $this->respond([
            'status' => true,
            'message' => 'Profile Image has been Updated Successfully!',
            'data' => [
                'userData' => User::getRecordById($request->user()->id)
            ]
        ]);
    }

    /**
     * @OA\Delete(
     * path="/api/remove-profile-image",
     * summary="Remove Profile Image",
     * description="Remove Profile Image",
     * operationId="removeProfileImage",
     * tags={"User"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Remove profile image data",
     *    @OA\JsonContent(
     *       required={"image","jwtToken"}
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Profile Image has been Removed Successfully!',
     *       'data': {
     *          userData: {
     *              'id': 2,
     *              'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *              'name': 'John Doe',
     *              'first_name': 'John',
     *              'last_name': Doe',
     *              'email': 'johndoe@gmail.com',
     *              'phone_no': '+11236547891',
     *              'role': null,
     *              'bio': 'john doe',
     *              'birth_date': null,
     *              'country': 'canada',
     *              'website': 'cloudrepai.com',
     *              'twitter': null,
     *              'facebook': null,
     *              'google': null,
     *              'linkedin': null,
     *              'instagram': null,
     *              'quora': null,
     *              'profile_photo': 'http://127.0.0.1:8000/storage/user/profile/SJDgCRdeoiG8isTkezKbyufF6rRaYb.png',
     *              'created_at': '2022-06-04T18:32:20.000000Z',
     *              'updated_at': '2022-06-04T18:36:16.000000Z',
     *              'deleted_at': null
     *          }
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Profile Image Not Found")
     *        )
     *     ),
     * )
     */

    public function removeProfileImage(Request $request)
    {
        $user = User::getRecordById($request->user()->id);
        removeImage('user/profile', $user->profile_photo_path);


        User::updateRecord('id', $request->user()->id, ['profile_photo_path' => null]);

        return $this->respond([
            'status' => true,
            'message' => 'Profile Image has been Removed Successfully!',
            'data' =>  [
                'userData' => User::getRecordById($request->user()->id)
            ]
        ]);
    }



    /**
     * @OA\Post(
     * path="/api/update-personal-information",
     * summary="update Personal Information",
     * description="Update Personal Information",
     * operationId="updatePersonalInformation",
     * tags={"User"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user data",
     *    @OA\JsonContent(
     *       required={"bio","birth_date","jwtToken"},
     *       @OA\Property(property="bio", type="string", format="bio", example="john doe"),
     *       @OA\Property(property="birth_date", type="datetime", format="birth_date", example="2022-06-04"),
     *       @OA\Property(property="country", type="string", format="country", example="canada"),
     *       @OA\Property(property="website", type="string", format="website", example="cloudrepai.com")
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Personal Information has been updated Successfully!',
     *       'data': {
     *          userData: {
     *              'id': 2,
     *              'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *              'name': 'John Doe',
     *              'first_name': 'John',
     *              'last_name': Doe',
     *              'email': 'johndoe@gmail.com',
     *              'phone_no': '+11236547891',
     *              'role': null,
     *              'profile_photo': uploads/users/1654705018327223787.jpg,
     *              'bio': 'john doe',
     *              'birth_date': null,
     *              'country': 'canada',
     *              'website': 'cloudrepai.com',
     *              'twitter': null,
     *              'facebook': null,
     *              'google': null,
     *              'linkedin': null,
     *              'instagram': null,
     *              'quora': null,
     *              'created_at': '2022-06-04T18:32:20.000000Z',
     *              'updated_at': '2022-06-04T18:36:16.000000Z',
     *              'deleted_at': null
     *          }
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User Not Found")
     *        )
     *     ),
     * )
     */
    public function personalInformation(Request $request)
    {
        $data = $request->validate([
            'bio' => 'required',
            'birth_date' => 'required',
            'country' => 'nullable',
            'website' => 'nullable',

        ]);
        User::updateRecord('id', $request->user()->id, $data);
        $user = User::getRecordById($request->user()->id);

        return $this->respond([
            'status' => true,
            'message' => 'Personal Information has been Updated Successfully!',
            'data' => $user
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/update-social-link",
     * summary="update Social Link",
     * description="Update Social Link",
     * operationId="updateSocialLink",
     * tags={"User"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user data",
     *    @OA\JsonContent(
     *       required={"jwtToken"},
     *       @OA\Property(property="twitter", type="string", format="twitter", example="twitter.com/john"),
     *       @OA\Property(property="facebook", type="string", format="facebook", example="facebook.com/john"),
     *       @OA\Property(property="google", type="string", format="google", example="google.com/john"),
     *       @OA\Property(property="instagram", type="string", format="instagram", example="instagram.com/john"),
     *       @OA\Property(property="linkedin", type="string", format="linkedin", example="linkedin.com/john"),
     *       @OA\Property(property="quora", type="string", format="quora", example="quora.com/john"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Social link has been updated Successfully!',
     *       'data': {
     *          userData: {
     *              'id': 2,
     *              'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *              'name': 'John Doe',
     *              'first_name': 'John',
     *              'last_name': Doe',
     *              'email': 'johndoe@gmail.com',
     *              'phone_no': '+11236547891',
     *              'role': null,
     *              'profile_photo': uploads/users/1654705018327223787.jpg,
     *              'bio': 'john doe',
     *              'birth_date': null,
     *              'country': 'canada',
     *              'website': 'cloudrepai.com',
     *              'twitter': null,
     *              'facebook': null,
     *              'google': null,
     *              'linkedin': null,
     *              'instagram': null,
     *              'quora': null,
     *              'created_at': '2022-06-04T18:32:20.000000Z',
     *              'updated_at': '2022-06-04T18:36:16.000000Z',
     *              'deleted_at': null
     *          }
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User Not Found")
     *        )
     *     ),
     * )
     */

    public function updateSocialLink(Request $request)
    {
        $data = $request->validate([
            'twitter' => 'nullable|url',
            'facebook' => 'nullable|url',
            'google' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'instagram' => 'nullable|url',
            'quora' => 'nullable|url',
        ]);




        User::updateRecord('id', $request->user()->id, $data);
        $user = User::getRecordById($request->user()->id);

        return $this->respond([
            'status' => true,
            'message' => 'Social link has been Updated Successfully!',
            'data' => $user
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/my-detail",
     * summary="Get Current User Detail",
     * description="Get Current User Detail",
     * operationId="GetCurrentUserDetail",
     * tags={"User"},
     * security={ {"sanctum": {} }},
     *  @OA\RequestBody(
     *    required=true,
     *    description="Pass user data",
     *    @OA\JsonContent(
     *       required={"jwtToken"}
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User has been Fetched Successfully!',
     *       'data': {
     *          userData: {
     *              'id': 2,
     *              'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *              'name': 'John Doe',
     *              'first_name': 'John',
     *              'last_name': Doe',
     *              'email': 'johndoe@gmail.com',
     *              'phone_no': '+11236547891',
     *              'role': null,
     *              'profile_photo': uploads/users/1654705018327223787.jpg,
     *              'bio': 'john doe',
     *              'birth_date': null,
     *              'country': 'canada',
     *              'website': 'cloudrepai.com',
     *              'twitter': null,
     *              'facebook': null,
     *              'google': null,
     *              'linkedin': null,
     *              'instagram': null,
     *              'quora': null,
     *              'created_at': '2022-06-04T18:32:20.000000Z',
     *              'updated_at': '2022-06-04T18:36:16.000000Z',
     *              'deleted_at': null
     *          }
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User Not Found")
     *        )
     *     ),
     * )
     */
    public function myDetail(Request $request)
    {

        $user = User::getRecordById($request->user()->id);

        return $this->respond([
            'status' => true,
            'message' => '',
            'data' => [
                'userData' => $user
            ]
        ]);
    }


    /**
     * @OA\Get(
     * path="/api/get-user-detail/{uuid}",
     * summary="Get User Detail By Uuid",
     * description="Get User Detail By uuid",
     * operationId="getUserByUuid",
     * tags={"Campaign"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of User",
     *    in="path",
     *    name="uuid",
     *    required=true,
     *    example="6e3cd84f-b3e5-4387-87da-0b7654f248d2",
     *    @OA\Schema(
     *       type="string",
     *       format="int64"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User has been Fetched Successfully!',
     *       'data': {
     *          'user_detail': {
     *              'uuid': 'd326b430-0197-4fcc-8be8-dc7a0c7d636f',
     *              'bussines_name': 'Isadora Simpson',
     *              'bussines_address': 'Autem veniam quo ex',
     *              'bussines_phone_no': '+1 (379) 665-4454',
     *              'google_my_bussines': '+1 (585) 395-4826',
     *              'crunchbase': 'Culpa excepteur mini',
     *              'linkedin': 'Veniam labore id ma',
     *              'twitter': 'Qui a recusandae Su',
     *              'step': 3,
     *              'user_id': 56,
     *              'user': {
     *                  'uuid': '2b57d931-d73e-41e7-b278-c2272c2513c4',
     *                  'name': 'Elaine Baxter',
     *                  'first_name': 'Elaine',
     *                  'last_name': 'Baxter',
     *                  'email': 'kyxe@mailinator.com',
     *                  'phone_no': '4878965412',
     *                  'role': null,
     *                  'profile_photo': {},
     *                  'created_at': '2022-07-13T19:21:21.000000Z'
     *              },
     *              'created_at': '2022-07-13T19:21:53.000000Z'
     *          }
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User Not Found")
     *        )
     *     ),
     * )
     */

    public function getClientDetail($uuid)
    {
        $user_id = User::getIdByUuid($uuid);
        $client_data = ClientProfileItem::where('user_id', $user_id)->first();
        if (empty($client_data)) {
            return $this->respond([
                'status' => false,
                'message' => 'User Detail Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'User Detail has been Fetched Successfully!',
            'data' => [
                'user_detail' => new ClientProfileItemResource($client_data)
            ],
        ]);
    }

    // public function getGamificationPoint(Request $request) {
    //     $gamification_points = Gamification::where('user_id',$request->user()->id)->sum('point');
    //     return $this->respond([
    //         'status' => true,
    //         'message' => 'Gamification Points has been fetched successfully!',
    //         'data' => [
    //             'gamification_points' => $gamification_points
    //         ],
    //     ]);
    // }

    public function updateNumber(Request $request)
    {
        $updateUserNumber = User::where('user_uuid', $request->uuid)->first();
        $user = $request->user();
        if ($user->hasRole('admin')) {
            $updateUserNumber->can_create_a_number = $request->boolean('can_create_a_number');
            $updateUserNumber->update();
            return $this->respond([
                'status' => true,
                'message' => 'User has successfully updated the number',
                'data' => $updateUserNumber
            ]);
        } else {
            return response()->json(['error' => 'Not authorized.'], 403);
        }
    }

    public function getWalletBalance(Request $request)
    {
        $wallet = Wallet::where('holder_id', $request->user()->id)->first();
        if ($wallet) {
            return $this->respond([
                'status' => true,
                'message' => 'wallet fetch successfully!',
                'wallet' => $wallet
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'NO wallet found!',
                'wallet' => []
            ]);
        }
    }
    public function addWalletBalance(Request $request)
    {

        // $wallet = Wallet::where('holder_id',$request->user()->id)->first();
        $user = $request->user();
        $wallet = $user->wallet;
        $wallet->meta = ['currency' => 'USD'];
        $wallet->balance = $wallet->balance + $request->balance;
        $wallet->save();
        $user->deposit($request->balance, ['description' => 'manual recharge']);
        return $this->respond([
            'status' => true,
            'message' => 'wallet added successfully!',
            'wallet' => $wallet
        ]);
    }
}
