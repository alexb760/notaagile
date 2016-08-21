<?php

namespace Incident\Models;

use Illuminate\Database\Eloquent\Model;

class LabelLang extends Model
{
    protected $fillable = ["label", "def", "lang_id"];

    public function lang()
    {
        return $this->belongsTo("Incident\Models\Lang");
    }
}
