<?php

namespace Featica\Tests\Models;

use Featica\HasFeatureFlags;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as AuthUser;
use Featica\Tests\Factories\UserFactory;

class User extends AuthUser
{
    use HasFeatureFlags;
    use HasFactory;

    protected $table = 'users';

    protected $guarded = [];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
