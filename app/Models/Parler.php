<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parler extends Model
{
    protected $table = 'parler';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_region', 'id_langue'];

    // Empêcher Laravel de chercher une clé primaire
    public function getKey()
    {
        return null;
    }

    public function getKeyName()
    {
        return null;
    }

    // Forcer la requête UPDATE à utiliser nos deux clés
    protected function setKeysForSaveQuery($query)
    {
        return $query
            ->where('id_region', '=', $this->getAttribute('id_region'))
            ->where('id_langue', '=', $this->getAttribute('id_langue'));
    }

    // Surcharge de la suppression
    public function delete()
    {
        return $this->newQuery()
            ->where('id_region', $this->id_region)
            ->where('id_langue', $this->id_langue)
            ->delete();
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'id_region', 'id_region');
    }

    public function langue()
    {
        return $this->belongsTo(Langue::class, 'id_langue', 'id_langue');
    }
}
