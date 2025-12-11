<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeContenu extends Model
{
    protected $primaryKey = 'id_type_contenu';
    protected $fillable = ['nom_contenu'];

    public function contenus()
    {
        return $this->hasMany(Contenu::class, 'id_type_contenu');
    }
}
