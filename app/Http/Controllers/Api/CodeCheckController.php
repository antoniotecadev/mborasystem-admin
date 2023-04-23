<?php

namespace App\Http\Controllers\Api;

use App\Models\ResetCodePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CodeCheckController extends BaseController
{
    public function codeCheck(Request $request) {
        try {
            $data = Validator::make($request->all(), [
                'code' => 'required|string|exists:reset_code_passwords',
            ]);

            if($data->fails()):
                $error['message'] = $data->errors();
                return $this->sendError('Erro de validação', $error);
            endif;

            // find the code
            $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

            // check if it does not expired: the time is one hour
            if ($passwordReset->created_at > now()->addHour()) {
                $passwordReset->delete();
                $error['message'] = 'Código inserido não é válido.';
                return $this->sendError('Código expirado', $error);
            }
            $success['message'] = 'Código válido';
            return $this->sendResponse($success, 'Código validado com sucesso');
        } catch (\Throwable $th) {
            $error['message'] = $th->getMessage();
            return $this->sendError('Código não validado', $error);
        } 
    }
}
