<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\OfficeResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::where([
            'number'    =>  $data['number']
        ])->first();

        if (!$user) {
            return response()->json([
                'success'   =>  false,
                'message'   =>  trans('auth.user_not_found')
            ]);
        }

        if (!Hash::check($data['password'], $user->password)) {
            return response()->json([
                'success'   =>  false,
                'message'   =>  trans('auth.failed')
            ]);
        }
        $res = $user->toArray();
        if ($user->office) {
            $res['office'] = new OfficeResource($user->office);
        }

        return response()->json([
            'success'   =>  true,
            'data'      =>  [
                'token' =>  $user->createToken('access_token')->plainTextToken,
                'user'  =>  $res
            ]
        ]);
    }
    public function me()
    {
        $user = auth()->user();
        $res = $user->toArray();
        if ($user->office) {
            $res['office'] = new OfficeResource($user->office);
        }
        return response()->json([
            'success'   =>  true,
            'data'      =>  $res
        ]);
    }
}
