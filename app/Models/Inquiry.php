<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = ['type','name','phone','email','subject','message','meta','status','handled_at'];

    protected function casts(): array
    {
        return ['meta' => 'array', 'handled_at' => 'datetime'];
    }
}
