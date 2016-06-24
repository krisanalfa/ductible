<?php

namespace Ductible\Models;

use Zeek\Ductible\Searchable\Elastic;
use Illuminate\Database\Eloquent\Model;
use Zeek\Ductible\Contracts\Searchable;

class Husband extends Model implements Searchable
{
    use Elastic;

    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function wife()
    {
        return $this->hasOne(Wife::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function children()
    {
        return $this->hasMany(Child::class, 'father_id');
    }
}
