<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use App\Traits\MacroableModel;
use App\Traits\Orderable;

class Discount extends Model
{
    protected $fillable = ['owner_id','owner_type','point_id','point_type','type','value','on_pure', 'ord'];

    public static $types = [
        'PERCENT',
        'CONSTANT'
    ];

    use MacroableModel;
    use SoftDeletes;

    //ORDERABLE
    use Orderable;
    public function orderableQryBld($qryBld = null) {
        $qryBld = $qryBld? clone $qryBld : $this;
        return $qryBld->where([
            [ 'point_id', $this->point_id ],
            [ 'point_type', $this->point_type ],
        ]);
    }
    //END ORDERABLE

    // @HOOK_TRAITS

    public function owner() {
        return $this->morphTo('owner');
    }

    public function point() {
        return $this->morphTo('point');
    }

    public function getValue($on) {
        if($this->type == 'PERCENT') {
            return $on*($this->value/100);
        }
        //CONSTANT
        return $this->value;
    }

    public function check() {
        if($owner = $this->owner) {
            return $owner->check_discount();
        }
        return true;
    }

    public static function mergeExceptFields() {
        return [
            'id',
            'deleted_at',
            'point_id',
            'point_type',
            'created_at',
            'updated_at',
        ];
    }

    public function merge(self $discount, $except = [], $only = false) {
        $attributes = is_array($only)? $discount->only($only) : $discount->getAttributes();
        $this->update(Arr::except($attributes, array_merge($except, static::mergeExceptFields() )));
    }


}
