<?php

namespace App\Http\Controllers\Api;

use App\Models\ResetCodePassword;
use App\Models\User;
use App\Notifications\SendCodeResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class SendCodeResetPasswordController extends BaseController
{
    public function sendCodeResetPassword(Request $request)
    {
        
        try {
            $data = Validator::make($request->all(), [
                'email' => 'required|email|exists:users',
            ], [
                'exists' => 'Usuário não encontrado.',
            ]);
    
            if($data->fails()):
                $error['message'] = $data->errors();
                return $this->sendError('Erro de validação', $error);
            endif;
            DB::beginTransaction();
            //Get user
            $user = User::where('email', $request->email)->first();

            // Delete all old code that user send before.
            ResetCodePassword::where('email', $request->email)->delete();

            // Get date with hours
            $request['created_at'] = now()->addHour();

            // Generate random code
            $request['code'] = mt_rand(100000, 999999);

            // Create a new code
            $codeData = ResetCodePassword::create($request->all());

            // Send email to user
            Notification::route('mail', [$request->email => $user->first_name . ' ' . $user->last_name])->notify(new SendCodeResetPasswordNotification($codeData->code));
            DB::commit();
            $success['message'] = 'Código enviado';
            return $this->sendResponse($success, 'Código enviado com sucesso');
        } catch (\Throwable $th) {
            DB::rollback();
            $error['message'] = $th->getMessage();
            return $this->sendError('Código não enviado', $error);
        }
    }
}
