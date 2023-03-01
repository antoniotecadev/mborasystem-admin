<?php

namespace App\Class;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class Enc {

    public static function encriptar($valor)
    {
        return Crypt::encryptString($valor);
    }

    public static function desencriptar($valorEncriptado) 
    {
        try {
            return Crypt::decryptString($valorEncriptado);
        } catch (DecryptException $e) {
            throw new DecryptException($e);
        }
    }

}