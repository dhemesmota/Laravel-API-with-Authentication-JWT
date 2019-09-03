<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * O getJWTIdentifier() é onde você diz qual é a coluna que servirá
     * como identificador para o usuário
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * O getJWTCustomClaims() é onde você vai dizer quais informações
     * você quer retornar no payload, exemplo:
     * return [
     *       'user' => [
     *          'id' => $this->id,
     *           'name' => $this->name,
     *           'email' => $this->email,
     *           'outra_informacao' => "qualquer_outra_coisa",
     *       ]
     *   ];
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
