<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriasMboraController extends Controller
{
    public function index(){
        return DB::table('categorias_mbora')->get();
    }
}
