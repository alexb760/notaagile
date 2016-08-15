<?php

namespace Incident\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = ['name', 'description', "label", "isVisible", "parent_id"];

    protected $cast = [
        "isVisible" => "boolean"
    ];
    
}
