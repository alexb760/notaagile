<?php

namespace Incident\Models;

use Illuminate\Database\Eloquent\Model;

class AppError extends Model
{
    protected $fillable = ["user_id", "php_class", "work_data", "message"];

    public function user()
    {
        return $this->belongsTo("Incident\Models\User");
    }
}
