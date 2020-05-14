<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AliadoUser extends Model
{
    /**
     * Specify the connection, since this implements multitenant solution
     * Called via constructor to faciliate testing
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('database.aliado_connection'));
    }

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function findById($id)
    {
        return $this->select('id', 'email','name','deleted_at as cancelled_at', 'created_at')
            ->where('id','=',$id)
            ->first();
    }

    public function findByEmail($email)
    {
        return $this->select('id', 'email','name','deleted_at as cancelled_at', 'created_at')
            ->where('email','=',$email)
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent Model Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contracargos() : HasMany
    {
        return $this->hasMany(ContracargosAliado::class, 'user_id')->latest();
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reps() : HasMany
    {
        return $this->hasMany(Repsaliado::class, 'user_id')->latest();
    }
}
