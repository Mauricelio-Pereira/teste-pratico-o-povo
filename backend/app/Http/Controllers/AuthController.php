<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Jobs\Auth\ResetPasswordCodeJob;
use App\Models\PasswordResetTokenModel;
use App\Models\UserModel;
use App\Utils\Helpers;
use Carbon\Carbon;
use DateTimeInterface;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;

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
        return $this->transaction(
            function () use ($request): ApiResponseResource {
                $email = $request->validated('email');
                $password = $request->validated('password');

                $user = $this->findUserByEmail($email);

                if (
                    !$user ||
                    !Helpers::isNonEmptyString($user->password) ||
                    !Hash::check($password, $user->password)
                ) {
                    throw new ApiException('Credenciais inválidas. Verifique as credenciais inseridas e tente novamente.', 401);
                }

                if ($user->inactive) {
                    throw new ApiException('Acesso não autorizado. Usuário inativo!', 401);
                }

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
                    'msg' => 'Login realizado com sucesso!',
                    'ok' => true
                ]);
            },
            'Ocorreu um erro ao realizar o login. Por favor, tente novamente mais tarde!'
        );
    }

    /**
     * Criar usuário
     *
     * @param RegisterRequest $request A requisição recebida
     *
     * @return ApiResponseResource O usuário recém criado
     */
    public function register(RegisterRequest $request): ApiResponseResource
    {
        return $this->transaction(
            function () use ($request): ApiResponseResource {
                $name = $request->validated('name');
                $email = $request->validated('email');
                $password = $request->validated('password');

                $user = new UserModel();
                $user->fill([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password
                ]);
                $user->save();

                $user = UserModel::find($user->id);

                $userResource = new UserResource($user);

                return new ApiResponseResource([
                    'code' => 201,
                    'data' => $userResource,
                    'msg' => 'Usuário cadastrado com sucesso!',
                    'ok' => true
                ]);
            },
            'Ocorreu um erro ao cadastrar o usuário!'
        );
    }
}
