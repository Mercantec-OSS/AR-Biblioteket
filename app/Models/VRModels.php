<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * Define the many-to-many relationship with Education.
     */
    public function educations()
    {
        return $this->belongsToMany(Education::class, 'education_vrmodel', 'vrmodel_id', 'education_id');
    }
}
