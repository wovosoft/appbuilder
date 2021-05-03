<?php

namespace App\Traits;


use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne};


trait UserTrait
{
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
