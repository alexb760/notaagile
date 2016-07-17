<?php

namespace Incident\Models;

use Illuminate\Database\Eloquent\Model;


class Profile extends Model
{
    protected $fillable = ['name', 'description', 'created_by'];

    public function creator()
    {
        return $this->belongsTo('Incident\Models\User', 'created_by','id');
    }
}
