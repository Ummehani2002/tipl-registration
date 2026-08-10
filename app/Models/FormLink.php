<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormLink extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'name'];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
