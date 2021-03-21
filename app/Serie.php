<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Serie extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'cover'];

    public function getCoverUrlAttribute() {
        if ($this->cover) {
            return Storage::url($this->cover);
        }
        return Storage::url('serie/noimage.jpg'); 
    }

    public function seasons() {
        return $this->hasMany(Season::class);
    }
}