<?php

namespace App\Http\Controllers\Api;

use App\Class\Enc;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            $enc = new Enc();
            $success['user_id'] = $enc->encriptar($user->id);
            $success['token'] =  $user->createToken($request->device_name)->plainTextToken;

            return $this->sendResponse($success, 'Conta de usuário criada com sucesso');

        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Erro de servidor', $error, 500 ); 
        }
    }
    // 1|XcHPPRdlilHD3J5eR4zULkgWHHAqcbffPYtLnKY8
    // 2|zui87UloDSbvxUe0rZ9H3g8uJmIdefYybMWVntRl
    // 3|Aw1GyU2caAwR2DBmy8qtEIacg8AGzcwdnY9uu0HW

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
                $enc = new Enc();
                $success['user_id'] = $enc->encriptar($user->id);
                $success['token'] =  $user->createToken($request->device_name)->plainTextToken;
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
}
