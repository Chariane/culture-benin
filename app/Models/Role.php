<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey='id';
    protected $fillable=['nom'];

    public function utilisateurs(){
        return $this->hasMany(Utilisateur::class,'id_role', 'id');
    }
}
