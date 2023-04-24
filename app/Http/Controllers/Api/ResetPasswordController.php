<?php

namespace App\Http\Controllers\Api;

use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends BaseController
{
    public function resetPassword(Request $request){
       
        try {
            $data = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed',
            ]);

            if($data->fails()):
                $error['message'] = $data->errors();
                return $this->sendError('Erro de validação', $error);
            endif;

            // find user's email 
            $user = User::firstWhere('email', $request->email);

            // update user password
            $user->update($request->only('password'));

            // Delete all old code that user send before.
            ResetCodePassword::where('email', $request->email)->delete();
            
            $success['message'] = 'Palavra - passe criada';
            return $this->sendResponse($success, 'Palavra - passe criada com sucesso');
        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Palavra - passe não criada.', $error);
        }

    }
}
