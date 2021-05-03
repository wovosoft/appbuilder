<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    public function getPermissionsAttribute($value): array
    {
        return is_array($value) ? $value : json_decode($value);
    }

    public function setPermissionsAttribute($value)
    {
        $this->attributes['permissions'] = is_array($value) ? json_encode($value) : $value;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
