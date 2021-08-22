<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'folder_id',
        'file'
    ];
    public function folder()
    {
        return $this->belongsTo(Folder::class,'folder_id','id');
    }
}
