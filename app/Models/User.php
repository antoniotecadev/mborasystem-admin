<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use League\Glide\Server;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use SoftDeletes, Authenticatable, Authorizable, HasFactory, HasApiTokens;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     * Preenchível
     * Somente os campos marcados como preenchíveis são usados na atribuição de massa.
     * Isso é feito para evitar ataques de dados de atribuição em massa quando o usuário envia dados da solicitação HTTP.
     * 
     * protected $fillable = [ 
     *      'name',
     *      'email',
     *      'password',
     *];
     *
     * Guardado
     * A propriedade deve conter uma matriz de atributos que você não deseja que sejam atribuíveis em massa. 
     * Todos os outros atributos que não estão na matriz serão atribuíveis em massa.
     * Caso vazia, todos os atributos não especificados serão atribuidos em massa 
     * 
     * protected $guarded = [];
     */
    

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    
    protected $casts = [
        'owner' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     * 
     * Ocultar colunas (na matriz) do banco de dados nos resultados das consultas. 
     * No exemplo, $hidden = ['senha'] torna a coluna 'senha' invisível nos resultados do usuário.
     */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function setPasswordAttribute($password)
    {
        if(!$password) return;

        $this->attributes['password'] = Hash::make($password);
    }

    public function setPhotoAttribute($photo)
    {
        if(!$photo) return;

        $this->attributes['photo_path'] = $photo instanceof UploadedFile ? $photo->store('users') : $photo;
    }

    public function getPhotoAttribute() {
        return $this->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']);
    }

    public function photoUrl(array $attributes)
    {
        if ($this->photo_path) {
            return URL::to(App::make(Server::class)->fromPath($this->photo_path, $attributes));
        }
    }

    public function isDemoUser()
    {
        return $this->email === 'antonioteca@hotmail.com';
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }

    public function scopeWhereRole($query, $role)
    {
        switch ($role) {
            case 'user': return $query->where('owner', false);
            case 'owner': return $query->where('owner', true);
        }
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        })->when($filters['role'] ?? null, function ($query, $role) {
            $query->whereRole($role);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
