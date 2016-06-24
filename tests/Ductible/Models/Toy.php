<?php

namespace Ductible\Models;

use Illuminate\Database\Eloquent\Model;

class Toy extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function children()
    {
        return $this->belongsToMany(Child::class);
    }
}
