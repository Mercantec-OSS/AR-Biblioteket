<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    public function vrModels()
    {
        return $this->belongsToMany(VRModel::class, 'education_vrmodel', 'education_id', 'vrmodel_id');
    }
}
