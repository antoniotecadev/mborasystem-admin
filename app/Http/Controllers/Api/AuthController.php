<?php

namespace App\Http\Controllers\Api;

use App\Class\Enc;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends BaseController
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'device_name' => 'required|string',
                'first_name' => 'required|string|min:4|max:15',
                'last_name' => 'required|string|min:4|max:20',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'password_confirmation' => 'required|min:8|same:password',
            ]);

            if($validator->fails()) {
                $error['message'] = $validator->errors();
                return $this->sendError('Erro de validação', $error); 
            }

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => $request->password,
                'account_id' => 2
            ]);
            $success['user_id'] = Enc::encriptar($user->id);
            $success['token'] =  $user->createToken($request->device_name)->plainTextToken;
            $success['name'] =  $user->first_name . ' ' . $user->last_name;
            $success['email'] =  $user->email;
            return $this->sendResponse($success, 'Conta de usuário criada com sucesso');

        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Erro de servidor', $error, 500 ); 
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validator->fails()) {
                $error['message'] = $validator->errors();
                return $this->sendError('Erro de validação', $error);
            }

            if(Auth::attempt($request->only(['email', 'password']))){
                $user = User::where('email', $request->email)->first();
                if(auth('sanctum')->check()):
                    $user->tokens()->delete();
                endif;
                $success['user_id'] = Enc::encriptar($user->id);
                $success['token'] =  $user->createToken($request->device_name)->plainTextToken;
                $success['name'] =  $user->first_name . ' ' . $user->last_name;
                $success['email'] =  $user->email;
                return $this->sendResponse($success, 'Usuário logado com sucesso'); 
            } else {
                $error['message'] = 'Email ou Palavra - passe errada';
                return $this->sendError('Falha ao entrar', $error);
            }

        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Erro de servidor', $error, 500); 
        }
    }

    public function logout(Request $request)
    {
        try {
            $accessToken = $request->bearerToken();
            $token = PersonalAccessToken::findToken($accessToken);
            $token->delete();
            $success['message'] = null;
            return $this->sendResponse($success, 'Usuário deslogado com sucesso'); 
        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Erro de servidor', $error, 500); 
        }
    }

    public function updateName(Request $request) {
        try {
            $validator = Validator::make($request->all(),[
                'first_name' => 'required|string|min:4|max:15',
                'last_name' => 'required|string|min:4|max:20',
            ]);

            if($validator->fails()) {
                $error['message'] = $validator->errors();
                return $this->sendError('Erro de validação', $error); 
            }

            User::where('id', auth()->user()->id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);
            $success['name'] =  $request->first_name . ' ' . $request->last_name;
            return $this->sendResponse($success, 'Alteração Guardada');
        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Erro de servidor', $error, 500 ); 
        }
    }

    public function updatePassword(Request $request) {
        try {
            $validator = Validator::make($request->all(),[
                'old_password' => 'required',
                'password' => 'required|min:8',
                'password_confirmation' => 'required|min:8|same:password',
            ]);

            if($validator->fails()) {
                $error['message'] = $validator->errors();
                return $this->sendError('Erro de validação', $error); 
            }

            if(!Hash::check($request->old_password, auth()->user()->password)){
                $error['message'] = ['old_password' => 'Palavra - passe errada'];
                return $this->sendError('Erro de validação', $error); 
            }

            User::where('id', auth()->user()->id)->update([
                'password' => Hash::make($request->password),
            ]);
            $success['message'] =  null;
            return $this->sendResponse($success, 'Palavra - passe alterada');
        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Erro de servidor', $error, 500 ); 
        }
    }
}
