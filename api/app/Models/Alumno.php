<?php

namespace Incident\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $fillable = ['nombres', 'apellidos', 'doc_identificacion'];

}
