<?php

namespace Ductible\Models;

use Illuminate\Database\Eloquent\Model;

class Wife extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function husband()
    {
        return $this->belongsTo(Husband::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function children()
    {
        return $this->hasMany(Child::class, 'mother_id');
    }
}
