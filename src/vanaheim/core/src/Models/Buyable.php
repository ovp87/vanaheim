<?php declare(strict_types=1);

namespace Vanaheim\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Buyable extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function item(): morphTo
    {
        return $this->morphTo('item', 'buyable_type', 'buyable_id');
    }
}
