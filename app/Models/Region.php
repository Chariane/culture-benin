<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regions';
    protected $primaryKey = 'id_region';
    protected $fillable = [
        'nom_region', 'description', 'population', 'superficie', 'localisation'
    ];

    public function contenus()
    {
        return $this->hasMany(Contenu::class, 'id_region');
    }

    public function langues()
    {
        return $this->belongsToMany(Langue::class, 'parler', 'id_region', 'id_langue');
    }
}

