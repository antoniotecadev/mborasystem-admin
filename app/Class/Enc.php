<?php

namespace App\Class;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class Enc {

    public function encriptar($valor){
        Crypt::encryptString($valor);
    }

    public function desencriptar($valorEncriptado){
        try {
            $decrypted = Crypt::decryptString($encryptedValue);
        } catch (DecryptException $e) {
            //
        }
    }

}