<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VRModels extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vrmodels'; // Change from 'models' to match migration

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'education',
        'description',
        'user_id',
        'model_path',
        'image_path',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
