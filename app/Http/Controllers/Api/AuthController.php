<?php

namespace App\Http\Controllers\Api;

use App\Actions\Fortify\CreateNewUser;
use App\Classes\Gamification;
use App\Models\User;
use App\Models\VerificationCode;
use BaconQrCode\Renderer\Color\Rgb;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\DB;

class AuthController extends ApiController
{
    public function getUserAttribute($user)
    {
        $data =  collect($user)->only('user_uuid', 'name', 'first_name', 'last_name', 'email', 'phone_no', 'profile_photo_path', 'step');
        $role = $user->getRoleNames()[0];
        // $role = 'admin';
        if ($role == 'admin') {
            $data['ability'] = [
                [
                    "action" => "manage",
                    "subject" => 'all'
                ]
            ];
        } else {
            $data['ability'] = [
                [
                    "action" => "all",
                    "subject" => $role
                ]
            ];
        }
        $data['role'] = $role;
        $data['is_verified_email'] = $user->hasVerifiedEmail();



        return $data;
    }
    public function getAuthResponseData($user)
    {
        $detail = $this->getUserAttribute($user);
        $token = $user->createToken('twilio-chat-app')->plainTextToken;
        $data['userData'] = $detail;
        $data['accessToken'] = $token;
        $data['refreshToken'] = $token;
        $data['isVerified2fa'] = false;
        return $data;
    }
    public function updateBasicInfo(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
        ]);

        if (!empty($request->password)) {
            $request->validate([
                'password' => 'required|confirmed|min:6',
            ]);

            $data['password'] = Hash::make($request->password);
        }


        DB::beginTransaction();
        try {
            $isNewEmail = ($request->user()->email != $request->email);
            User::updateRecord('id', $request->user()->id, $data);

            //reverify email if email is changed
            if ($isNewEmail) {
                // update the step of signup
                User::updateRecord('id', $request->user()->id, ['step' => 3, 'email_verified_at' => null]);
                $user = User::getRecordById($request->user()->id);
                event(new Registered($user));
            } else {
                $user = User::getRecordById($request->user()->id);
            }

            $data = $this->getUserAttribute($user);;

            DB::commit();
            return $this->respond([
                'status' => true,
                'message' => 'User Has been updated Successfully!',
                'data' => $data
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->respond([
                'status' => false,
                'message' => $e->getMessage(),
                'data' =>  []
            ]);
        }




        //       first_name: this.first_name,
        //       last_name: this.last_name,
        //       email: this.userEmail,
        //       password: this.password,
        //       password_confirmation: this.password_confirmation,
        //       role: get("role"),


    }
    /**
     * @OA\Post(
     * path="/api/register",
     * summary="Sign up",
     * description="Register User",
     * operationId="authRegister",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user data",
     *    @OA\JsonContent(
     *       required={"first_name","last_name","email","password","password_confirmation","role"},
     *       @OA\Property(property="first_name", type="string", format="first_name", example="john"),
     *       @OA\Property(property="last_name", type="string", format="last_name", example="doe"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="password_confirmation", type="string", format="password_confirmation", example="PassWord12345"),
     *       @OA\Property(property="role", type="string", format="role", example="agent"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User has been Registered Successfully!',
     *       'data': {
     *          'userData': {
     *              'id': 2,
     *              'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *              'name': 'John Doe',
     *              'first_name': 'John',
     *              'last_name': Doe',
     *              'email': 'johndoe@gmail.com',
     *              'phone_no': '+11236547891',
     *              'step': '3',
     *              'profile_photo': 'https://ui-avatars.com/api/?name=j+j&color=7F9CF5&background=EBF4FF',
     *              'ability': [
     *                  {
     *                      'action': 'all',
     *                      'subject': 'agent'
     *                  }
     *              ],
     *              'role': 'agent',
     *              'is_verified_email': false
     *         },
     *         accessToken':  '18|x6Qlq7pZfG9TcOuFyZBR6PAZZZyHZjbs8h6Sa9Ar',
     *         refreshToken': '18|x6Qlq7pZfG9TcOuFyZBR6PAZZZyHZjbs8h6Sa9Ar',
     *         isVerified2fa': false,
     *     }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User Not Found")
     *        )
     *     )
     * ),
     */
    public function register(Request $request)
    {
        DB::beginTransaction();
        $user = (new CreateNewUser())->create($request->all());
        $gamification = new Gamification();
        $gamification->add($request, $user->id, 20, 'New User Registration', true);
        try {

            event(new Registered($user));
            DB::commit();
            $data = $this->getAuthResponseData($user);


            return $this->respond([
                'status' => true,
                'message' => 'User Has been Register  Successfully!',
                'data' => $data
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->respond([
                'status' => false,
                'message' => $e->getMessage(),
                'data' =>  []
            ]);
        }
    }
    public function registerByAdmin(Request $request)
    {
        if (!$request->user()->hasRole('admin')) {
            return $this->respondInvalidRequest();
        }
        $user = (new CreateNewUser())->createdByAdmin($request->all());
        $gamification = new Gamification();
        $gamification->add($request, $user->id, 20, 'New User Registration', true);
        try {

            $data = $this->getAuthResponseData($user);


            return $this->respond([
                'status' => true,
                'message' => 'User Has been Register  Successfully!',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return $this->respond([
                'status' => false,
                'message' => $e->getMessage(),
                'data' =>  []
            ]);
        }
    }
    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User has been Login Successfully!',
     *       'data': {
     *          'userData': {
     *              'id': 2,
     *              'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *              'name': 'John Doe',
     *              'first_name': 'John',
     *              'last_name': Doe',
     *              'email': 'johndoe@gmail.com',
     *              'phone_no': '+11236547891',
     *              'step': '3',
     *              'profile_photo': 'https://ui-avatars.com/api/?name=j+j&color=7F9CF5&background=EBF4FF',
     *              'ability': [
     *                  {
     *                      'action': 'all',
     *                      'subject': 'agent'
     *                  }
     *              ],
     *              'role': 'agent',
     *              'is_verified_email': false
     *         },
     *         accessToken':  '18|x6Qlq7pZfG9TcOuFyZBR6PAZZZyHZjbs8h6Sa9Ar',
     *         refreshToken': '18|x6Qlq7pZfG9TcOuFyZBR6PAZZZyHZjbs8h6Sa9Ar',
     *         isVerified2fa': false,
     *     }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The provided credentials are incorrect.")
     *        )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $data = $this->getAuthResponseData($user);

        VerificationCode::generatedVerificationCode($user->id);

        return $this->respond([
            'status' => true,
            'message' => 'User has been login successfully!',
            'data' => $data
        ]);
    }

    public function loginAs(Request $request)
    {

        $admin = $request->user();
        if (!in_array('admin', $admin->getRoleNames()->toArray())) {
            return $this->respondInvalidRequest();
        }
        $request->validate([
            'user_uuid' => 'required|uuid',
        ]);
        $user = User::where('user_uuid', $request->user_uuid)->first();

        $data = $this->getAuthResponseData($user);

        return $this->respond([
            'status' => true,
            'message' => 'User has been login successfully!',
            'data' => $data
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/forgot-password",
     * summary="Forgot Password",
     * description="Forgot Password by email",
     * operationId="forgotPassword",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass email",
     *    @OA\JsonContent(
     *       required={"email"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="A password reset link has been sent to this email successfully!")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="We can't find a user with that email address")
     *        )
     *     )
     * )
     */

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink(
            $request->only('email')
        );
        $statusType = $status === Password::RESET_LINK_SENT ? true : false;

        return $this->respond([
            'status' => $statusType,
            'message' => __($status),
            'data' => []
        ]);
    }
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->respond([
                'status' => false,
                'message' => 'This email is already Verified',
                'data' => []
            ]);
            // return $request->wantsJson()
            //     ? new JsonResponse('', 204)
            //     : redirect()->intended(config('fortify.home'));
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->respond([
            'status' => true,
            'message' => 'Verfication email has been send to you, please check your mail Inbox',
            'data' => []
        ]);
    }
    public function VerificationEmail(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            $user = $this->getUserAttribute($request->user());
            $data['userData'] = $user;


            return response()->json(['status' => false, 'message' => 'Your email already verified', 'data' => $data]);
        } else {

            $request->fulfill();
            User::where('id', $request->user()->id)->update(['step' => 4]);
            $data = $this->getAuthResponseData(User::getRecordById($request->user()->id));
            $data['isVerified2fa'] = true;

            return $this->respond([
                'status' => true,
                'message' => 'User has been login successfully!',
                'data' => $data
            ]);
        }
    }

    /**
     * @OA\Post(
     * path="/api/reset-password",
     * summary="Reset Password",
     * description="Reset Password email, password, token",
     * operationId="resetPassword",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password","token"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="password_confirmation", type="string", format="password_confirmation", example="PassWord12345"),
     *       @OA\Property(property="token", type="string", format="token", example="7d87da871a3ac2a16856ef135ed870255dad10e1eec09c1d04c5bdfc2bfad3c7"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User password has been reset successfully!")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong credentials. Please try again")
     *        )
     *     )
     * )
     */

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['status' => true, 'message' => __($status), 'data' => []]);
        } else {
            return response()->json(['status' => false, 'message' => __($status), 'data' => []]);
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);


        $status = Hash::check($request->old_password, $request->user()->password);

        if ($status) {
            User::where('id', $request->user()->id)->update(['password' => Hash::make($request->password)]);
            return response()->json(['status' => true, 'message' => 'Password has been updated successfully!', 'data' => []]);
        } else {
            return response()->json(['status' => false, 'message' => 'Old password not correct!', 'data' => []]);
        }
    }




    public function isVerifiedEmail(Request $request)
    {
        $verify = User::where('id', $request->user()->id)->value('email_verified_at');
        $data['isVerified'] = !is_null($verify);

        return response()->json(['status' => true, 'message' => '', 'data' => $data]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->respond([
            'status' => true,
            'message' => 'user has been logout successfully!',
            'data' => []
        ]);
    }
    public function refresh(Request $request)
    {
        $data['accessToken'] = $request->user()->createToken('refresh_token')->plainTextToken;
        return response()->json(['status' => true, 'message' => '', 'data' => $data]);
    }

    /**
     * @OA\Get(
     * path="/api/auth/user",
     * summary="Get Auth User",
     * description="Get Auth User",
     * operationId="getAuthUser",
     * tags={"User"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="get user by auth user id",
     *    in="query",
     *    name="id",
     *    example="test user",
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

    public function userDetail(Request $request)
    {
        $data = User::where('id', $request->user()->id)->first();
        $user = collect($data)->only('user_uuid', 'name', 'email', 'profile_photo_url');
        return $this->respond([
            'status' => true,
            'message' => 'User Has been Register Successfully!',
            'data' => [
                'user' => $user
            ]
        ]);
    }
    public function getTwoFa(Request $request)
    {
        $code = VerificationCode::generatedVerificationCode($request->user()?->id);

        return $this->respond([
            'status' => true,
            'message' => 'OTP has been send on your register number',
            'data' => [
                'code' => $code
            ]
        ]);
    }

    public function verifyTwoFa(Request $request)
    {
        $request->validate([
            'code' => 'required|min:4|max:4',
        ]);
        $result =  VerificationCode::verifyTwoFa($request->user()?->id, $request->code);

        if ($result) {
            User::updateStep($request->user()->id, 6);
        }

        return $this->respond([
            'status' => $result == 1 ? true : false,
            'message' => $result == 1 ? 'OTP has been verified Successfully!' : 'OTP is not correct please try again!',
            'data' => []
        ]);
    }

    /**
     * @OA\Put(
     * path="/api/update-phone-no",
     * summary="Update Phone No",
     * description="Update Phone No",
     * operationId="updatePhone",
     * tags={"User"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user phone no",
     *    @OA\JsonContent(
     *       required={"phone_no"},
     *       @OA\Property(property="phone_no", type="integer", format="phone", example="123456789"),
     *    ),
     * ),
     * @OA\Response(
     *      response=201,
     *       description="Your phone no has been Register and OPT send on it!",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
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

    public function updatePhoneNumber(Request $request)
    {
        $request->validate([
            'phone_no' => 'required|min:12|max:12|unique:users,phone_no,' . $request->user()->id,
        ]);

        User::where('id', $request->user()->id)->update(['phone_no' => $request->phone_no]);
        $user = User::where('id', $request->user()->id)->first();
        if ($request->phone_no != $request->user()->phone_no || VerificationCode::isVerified($user->id)) {
            VerificationCode::generatedVerificationCode($user->id);

            User::where('id', $request->user()->id)->update(['step' => 5]);
        } else {
            User::where('id', $request->user()->id)->update(['step' => 6]);
        }
        $user = User::where('id', $request->user()->id)->first();

        // throw new Exception("Value must be 1 or below");
        return $this->respond([
            'status' => true,
            'message' => 'Your phone no has been Register and OPT send on it!',
            'data' => [
                'user' => $user
            ]
        ]);
    }
    public function twilioWebhook()
    {
        $input = (file_get_contents('php://input'));

        DB::table('twilio_response')->insert([
            'body' => $input
        ]);
    }
}
