<?php

namespace Incident\Models;

use Illuminate\Database\Eloquent\Model;

class Lang extends Model
{
    protected $fillable = ["code", "name"];

    public function labels()
    {
        return $this->hasMany("Incident\Models\LabelLang");
    }
}
