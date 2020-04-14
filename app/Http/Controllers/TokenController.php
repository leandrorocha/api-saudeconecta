<?php

namespace App\Http\Controllers;

use App\User;
use App\Whitelist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TokenController extends Controller
{

    public function getCSRFToken() {
        return response([
            '_token' => csrf_token()
        ]);
    }



    public function create(Request $request)
    {
        $request->validate([
            'cpf' => 'required|min:11|max:11|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'device_name' => 'required'
        ]);

        $cpfInWhitlist = Whitelist::where('cpf', $request->input('cpf'))->first();

        if(!$cpfInWhitlist) {
            throw ValidationException::withMessages([
                'CPF' => ['CPF não consta na lista de permissão']
            ]);
        }

        $user = User::create([
            'cpf' => $request->input('cpf'),
            'email' => $request->input('cpf'),
            'name' => $request->input('name'),
            'password' => Hash::make($request->input('password')),
            'device_name' => $request->input('device_name'),
        ]);

        $token = $this->createToken($user, $request->input('device_name'));

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

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

    public function messages()
    {
        
    }
}
