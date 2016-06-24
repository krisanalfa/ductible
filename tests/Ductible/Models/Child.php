<?php

namespace Ductible\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function father()
    {
        return $this->belongsTo(Husband::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function mother()
    {
        return $this->belongsTo(Wife::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function toys()
    {
        return $this->belongsToMany(Toy::class);
    }
}
