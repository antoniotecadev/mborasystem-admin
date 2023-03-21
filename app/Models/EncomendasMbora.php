<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EncomendasMbora extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'client_coordinate' => 'array',
        'estado' => 'boolean'
    ];

    protected $table = 'encomendas_mbora';

    protected $fillable = ['imei_contacts', 'id_users_mbora', 'id_produtos_mbora', 'client_phone', 'client_address', 'client_info_ad', 'client_coordinate'];
}
