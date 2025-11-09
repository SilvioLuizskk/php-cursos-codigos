<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * POST /api/auth/register
     * Registrar novo usuário
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            // Criar usuário
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'status' => 'active',
            ]);

            // Gerar token JWT
            $token = $user->createToken('auth_token')->plainTextToken;

            // Log da ação
            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);

            // Disparar evento de registro (para emails de boas-vindas)
            // event(new \App\Events\UserRegistered($user));

            return response()->json([
                'message' => 'Usuário registrado com sucesso',
                'user' => new UserResource($user),
                'token' => $token,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Erro ao registrar usuário',
            ], 500);
        }
    }

    /**
     * POST /api/auth/login
     * Login do usuário
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            // Buscar usuário
            $user = User::where('email', $request->email)->first();

            // Validar credenciais
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Credenciais inválidas'],
                ]);
            }

            // Verificar se usuário está ativo
            if ($user->status !== 'active') {
                return response()->json([
                    'message' => 'Conta desativada. Entre em contato com o suporte.',
                ], 403);
            }

            // Atualizar último login
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Gerar token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Log da ação
            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Login realizado com sucesso',
                'user' => new UserResource($user),
                'token' => $token,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Credenciais inválidas',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Login failed', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Erro ao fazer login',
            ], 500);
        }
    }

    /**
     * POST /api/auth/logout
     * Logout do usuário
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Revogar token atual
            $request->user()->currentAccessToken()->delete();

            // Log da ação
            Log::info('User logged out successfully', [
                'user_id' => $request->user()->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Logout realizado com sucesso',
            ]);

        } catch (\Exception $e) {
            Log::error('Logout failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Erro ao fazer logout',
            ], 500);
        }
    }

    /**
     * POST /api/auth/refresh
     * Renovar token JWT
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            // Verificar se usuário ainda está ativo
            if (!$user->is_active) {
                return response()->json([
                    'message' => 'Conta desativada',
                ], 403);
            }

            // Revogar token atual
            $request->user()->currentAccessToken()->delete();

            // Gerar novo token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Log da ação
            Log::info('Token refreshed successfully', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Token renovado com sucesso',
                'token' => $token,
                'user' => new UserResource($user),
            ]);

        } catch (\Exception $e) {
            Log::error('Token refresh failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Erro ao renovar token',
            ], 500);
        }
    }

    /**
     * GET /api/auth/user
     * Obter dados do usuário autenticado
     */
    public function user(Request $request): JsonResponse
    {
        try {
            return response()->json([
                'user' => new UserResource($request->user()),
            ]);

        } catch (\Exception $e) {
            Log::error('Get user failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);

            return response()->json([
                'message' => 'Erro ao obter dados do usuário',
            ], 500);
        }
    }

    /**
     * POST /api/auth/logout-all
     * Revogar todos os tokens do usuário
     */
    public function logoutAll(Request $request): JsonResponse
    {
        try {
            // Revogar todos os tokens
            $request->user()->tokens()->delete();

            // Log da ação
            Log::info('User logged out from all devices', [
                'user_id' => $request->user()->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Logout realizado em todos os dispositivos',
            ]);

        } catch (\Exception $e) {
            Log::error('Logout all failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Erro ao fazer logout em todos os dispositivos',
            ], 500);
        }
    }
}
