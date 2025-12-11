<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'medias';
    protected $primaryKey = 'id_media';
    public $timestamps = false;

    protected $fillable = [
        'chemin',
        'id_type_media',
        'id_contenu',
        'id_langue',
    ];

    /**
     * Type du média
     */
    public function typeMedia()
    {
        return $this->belongsTo(TypeMedia::class, 'id_type_media', 'id_type_media');
    }

    public function langue()
    {
        return $this->belongsTo(Langue::class, 'id_langue', 'id_langue');
    }

    /**
     * Contenu lié au média
     */
    public function contenu()
    {
        return $this->belongsTo(Contenu::class, 'id_contenu', 'id_contenu');
    }
}
