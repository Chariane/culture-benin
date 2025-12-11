<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;


class TypeMedia extends Model
{   
    protected $table = 'type_medias';
    protected $primaryKey = 'id_type_media';
    protected $fillable = ['nom_media'];

    public function medias()
    {
        return $this->hasMany(Media::class, 'id_type_media');
    }
    

}

