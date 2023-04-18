<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FavoritosMbora extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'favoritos_mbora';
    protected $fillable = ['id_users_mbora', 'id_products_mbora'];
}
