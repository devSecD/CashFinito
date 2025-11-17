<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SetCurrencyRequest;

use App\Http\Resources\UserResource;

use App\Models\User;
use App\Models\UserSetting;

use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    /**
     * Registrar nuevo usuario
     */
    public function register(RegisterRequest $request): JsonResponse 
    {
        // Crear usuario
        $user = User::create([
            'name' => $request->name, 
            'email' => $request->email, 
            'password' => Hash::make($request->password), 
        ]);

        // Crear configuración de moneda base
        UserSetting::create([
            'user_id' => $user->id, 
            'currency_id' => $request->currency_id, 
        ]);

        $user = $user->fresh(['setting', 'setting.currency']);

        // Generar token
        $token = $user->createToken('auth_token ')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente', 
            // 'user' => new UserResource($user), 
            'user' => new UserResource($user->load('setting', 'setting.currency')),
            'access_token' => $token, 
            'token_type' => 'Bearer'
        ], 201);
    }

    /**
     * Iniciar sesión
     */
    public function login(LoginRequest $request): JsonResponse 
    {
        // Intentar autenticar
        if(!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'], 
            ]);
        }

        // Obtener usuario autenticado
        $user = User::where('email', $request->email)->first();

        // Revocar tokens anteriores (opcional, por seguridad)
        $user->tokens()->delete();

        // Generar nuevo token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso', 
            'user' => new UserResource($user->load('setting', 'setting.currency')), 
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request): JsonResponse 
    {
        // Revocar token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente', 
        ]);
    }

    /**
     * Obtener usuario autenticado
     */
    public function me(Request $request): JsonResponse 
    {
        return response()->json([
            'user' => new UserResource($request->user()->load('setting', 'setting.currency')), 
        ]);
    }

    /**
     * Actualizar perfil
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse 
    {
        $user = $request->user();

        $user->update($request->validated());

        return response()->json([
            'message' => 'Perfil actualizado exitosamente', 
            'user' => new UserResource($user->load('setting', 'setting.currency')),
        ]);
    }

    /**
     * Solicitar reset de contraseña
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse 
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Hemos enviado un enlace de recuperación a tu correo', 
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [__($status)], 
        ]);
    }

    /**
     * Resetear contraseña
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse 
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'), 
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                // Revocar todos los tokens
                $user->tokens()->delete();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Contraseña restablecida exitosamente', 
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [__($status)], 
        ]);
    }

    /**
     * Configurar/actualizar moneda base
     */
    public function setCurrency(SetCurrencyRequest $request): JsonResponse 
    {
        $user = $request->user();

        // Buscar o crear configuración
        $setting = UserSetting::firstOrCreate(
            ['user_id' => $user->id], 
            ['currency_id' => $request->currency_id]
        );

        // Actualizar si ya existe
        if (!$setting->wasRecentlyCreated) {
            $setting->update(['currency_id' => $request->currency_id]);
        }

        return response()->json([
            'message' => 'Moneda base actualizada exitosamente', 
            'user' => new UserResource($user->load('setting.currency')), 
        ]);
    }
}
