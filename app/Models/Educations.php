<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Educations extends Model
{

    protected $fillable = ['title', 'color'];

    public function vrModels()
    {
        return $this->belongsToMany(VRModel::class, 'education_vrmodel', 'education_id', 'vrmodel_id');
    }
}
