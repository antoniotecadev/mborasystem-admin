<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ResetCodePassword extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'reset_code_passwords';

    protected $fillable = [
        'email',
        'code',
        'created_at',
    ];

    public $timestamps = false;
}
