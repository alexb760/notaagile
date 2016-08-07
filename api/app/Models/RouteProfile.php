<?php

namespace Incident\Models;

use Illuminate\Database\Eloquent\Model;

class RouteProfile extends Model
{
    protected $fillable = ['profile_id', 'route_id'];

    public function profile()
    {
        return $this->belongsTo('Incident\Models\Profile');
    }

    public function route()
    {
        return $this->belongsTo('Incident\Models\Route');
    }
}
