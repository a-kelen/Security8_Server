<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    public function passwords() {
        return $this->hasMany('App\Password');
    }
}
