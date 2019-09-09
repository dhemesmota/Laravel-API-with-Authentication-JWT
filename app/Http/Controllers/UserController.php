<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    /**
     * Gera um token de autorização se o usuário for encontrado no banco de dados.
     * Ele gera um erro se o usuário não for encontrado ou se ocorrer uma exceção
     * ao tentar encontrar o usuário.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'), 200);
    }

    /**
     * Valida a entrada do usuário e cria um usuário se as credenciais forem
     * validadas.
     * Então o usuário é passado para JWTAuth para gerar um token de acesso.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:254',
            'email' => 'required|string|email|max:99|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'), 201);
    }

    /**
     * Retorna o objeto de usuário com base no token de autorização
     * passado.
     * JWT
     */
    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'), 200);
    }

    /**
     * Logout
     */
    public function logout()
    {
        JWTAuth::invalidate();
        return response()->json([
            'status' => 200,
            'statusMessage' => 'success',
            'message' => 'User Logged Out',
        ], 200);
    }

    public function users()
    {
        $users = User::all();
        return response()->json(compact('users'), 200);
    }
}
