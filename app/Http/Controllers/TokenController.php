<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TokenController extends Controller
{
    public function token(Request $request)
    {
        $this->validateForm($request);

        list($cpf, $password, $deviceName) = $this->data($request);

        $user = User::where('cpf', $cpf)->first();

        $this->checkPassword($user, $password);

        $token = $this->createToken($user, $deviceName);

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function data(Request $request): array
    {
        $cpf = $request->input('cpf');
        $password = $request->input('password');
        $deviceName = $request->input('device_name');

        return [$cpf, $password, $deviceName];
    }

    /**
     * @param User $user
     * @param string $password
     * @throws ValidationException
     */
    public function checkPassword(User $user, $password): void
    {
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'cpf' => ['The provided credentials are incorrect'],
            ]);
        }
    }

    /**
     * @param Request $request
     */
    public function validateForm(Request $request): void
    {
        $request->validate([
            'cpf' => 'required',
            'password' => 'required',
            'device_name' => 'required'
        ]);
    }

    /**
     * @param User $user
     * @param string $deviceName
     * @return string
     */
    public function createToken(User $user, $deviceName)
    {
        return $user->createToken($deviceName)->plainTextToken;
    }
}
