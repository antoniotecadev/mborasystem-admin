<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class EncomendasMbora extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $casts = [
        'client_coordinate' => 'array',
        'estado' => 'boolean'
    ];

    protected $table = 'encomendas_mbora';

    protected $fillable = ['code', 'imei_contacts', 'id_users_mbora', 'id_produts_mbora', 'prod_quant', 'client_phone', 'client_address', 'client_info_ad', 'client_coordinate'];
}
