<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class SeguidoresEmpresasMboraController extends Controller
{
    public function getNumberFollowers($imei) {
        $seguidores = Contact::where('imei', $imei)->first('followers_mbora');
        return $seguidores->followers_mbora;
    }
}
