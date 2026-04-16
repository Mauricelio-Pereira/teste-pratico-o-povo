<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Realizar login dos usuário
     *
     * @param LoginRequest $request A requisição recebida
     *
     * @return ApiResponseResource O token de acesso gerado
     */
    public function login(LoginRequest $request): ApiResponseResource
    {
        $email = $request->validated('email');
        $password = $request->validated('password');

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return new ApiResponseResource([
                'code' => 401,
                'data' => null,
                'msg' => 'Credenciais inválidas. Verifique as credenciais inseridas e tente novamente.',
                'ok' => false
            ]);
        }

        $tokenExpiresAt = Carbon::now()->addDay();
        $token = $user->createToken('api-token', ['*'], $tokenExpiresAt);

        $tokenResource = new TokenResource([
            'text' => $token->plainTextToken,
            'expiresAt' => $tokenExpiresAt->format('Y-m-d H:i:s')
        ]);

        return new ApiResponseResource([
            'code' => 200,
            'data' => $tokenResource,
            'msg' => 'Login realizado com sucesso!',
            'ok' => true
        ]);
    }


    /**
     * Realizar o logout do usuário
     *
     * @return ApiResponseResource
     */
    public function logout(): ApiResponseResource
    {
        return $this->transaction(
            function (): ApiResponseResource {
                $user = $this->getAuthenticatedUser();
                $user
                    ->currentAccessToken()
                    ->delete();

                return new ApiResponseResource([
                    'code' => 200,
                    'msg' => 'Logout realizado com sucesso!',
                    'ok' => true
                ]);
            },
            'Ocorreu um erro ao realizar o logout!'
        );
    }

    /**
     * Atualizar o token de autenticação do usuário
     *
     * @return ApiResponseResource O novo token de acesso gerado
     */
    /*public function refreshToken(): ApiResponseResource
    {
        return $this->transaction(
            function (): ApiResponseResource {
                $user = $this->getAuthenticatedUser();
                $tokenExpiresAt = Carbon::now()
                    ->addDay();
                $token = $this->createPersonalAccessToken($user, $tokenExpiresAt);

                $tokenResource = new TokenResource([
                    'text' => $token->plainTextToken,
                    'expiresAt' => $tokenExpiresAt->format('Y-m-d H:i:s')
                ]);

                return new ApiResponseResource([
                    'code' => 200,
                    'data' => $tokenResource,
                    'msg' => 'Token atualizado com sucesso!',
                    'ok' => true
                ]);
            },
            'Ocorreu um erro ao atualizar o token de autenticação!'
        );
    }
    */

    /**
     * Criar usuário
     *
     * @param RegisterRequest $request A requisição recebida
     *
     * @return ApiResponseResource O usuário recém criado
     */
    public function register(RegisterRequest $request): ApiResponseResource
    {
        $name = $request->validated('name');
        $email = $request->validated('email');
        $password = $request->validated('password');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        $userResource = new UserResource($user);

        return new ApiResponseResource([
            'code' => 201,
            'data' => $userResource,
            'msg' => 'Usuário cadastrado com sucesso!',
            'ok' => true
        ]);
    }
}
