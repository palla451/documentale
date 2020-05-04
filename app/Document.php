<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['name', 'path'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data_insert' => 'datetime'
    ];


    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        $url = 'storage/'. $this->path;
        return $url;
    }
}
