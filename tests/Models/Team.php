<?php

namespace Featica\Tests\Models;

use Featica\HasFeatureFlags;
use Featica\Tests\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFeatureFlags;
    use HasFactory;

    protected $table = 'teams';

    protected $guarded = [];

    protected $casts = [
        'feature_flags' => 'json',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return TeamFactory::new();
    }
}
