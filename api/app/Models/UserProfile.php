<?php

namespace Incident\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = ['user_id', 'profile_id'];

    public function user()
    {
        return $this->belongsTo('Incident\Models\User');
    }

    public function profile()
    {
        return $this->belongsTo('Incident\Models\Profile');
    }
}
