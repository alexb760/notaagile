<?php

namespace Incident\Http\Controllers;

use Illuminate\Http\Request;

use Incident\Http\Requests;
use Incident\Http\Controllers\Controller;

class UserProfileController extends BaseController
{

    protected $model = 'Incident\Models\UserProfile';
    protected $eager = array('user', 'profile');

    protected function getRules(Request $request)
    {
        return ['user_id' => 'required', 'profile_id' => 'required'];
    }

    protected function getMessages()
    {
        return ['user_id.required' => 'El id de usuario es requerido',
            'profile_id.required' => 'El id del perfil es requerido'
        ];
    }
}
