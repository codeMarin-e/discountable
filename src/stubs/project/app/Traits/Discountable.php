<?php
    namespace App\Traits;

    use Illuminate\Pipeline\Pipeline;
    use App\Models\Discount;

    trait Discountable {

        public static $discounts_before = [];
        public static $discounts_after = [];

        public static function bootDiscountable() {
            static::deleting( static::class.'@onDeleting_discounts' );
            if (method_exists(static::class, 'bootSoftDeletes') || static::hasMacro('bootSoftDeletes')) {
                static::registerModelEvent('forceDeleted', static::class . '@onForceDeleted_discounts');
            }
        }

        public function discounts() {
            return $this->morphMany( Discount::class, 'point')->orderBy('ord', 'ASC');;
        }

        public function addDiscount($attributes = []) {
            return $this->discounts()->create($attributes);
        }

        public function onDeleting_discounts($model) {
            if(!method_exists(static::class, 'bootSoftDeletes') && !static::hasMacro('bootSoftDeletes')) {
                return $model->onForceDeleted_discounts($model);
            }
            foreach($model->discounts()->get() as $discount) {
                $discount->delete();
            }
        }

        public function onForceDeleted_discounts($model) {
            foreach($model->discounts()->withTrashed()->get() as $discount) {
                $discount->forceDelete();
            }
        }

        public function activeDiscounts() {
            $return = [];
            foreach($this->getDiscounts() as $discount) {
                if(!$discount->check()) {
                    continue;
                }
                $return[] = $discount;
            }
            return $return;
        }

        public function getDiscounts() {
            $return = app(Pipeline::class)
                ->send([$this, []])
                ->through(static::$discounts_before)
                ->then(function($data) {
                    list($object, $otherDiscounts) = $data;
                    return $otherDiscounts;
                });
            return app(Pipeline::class)
                ->send([$this, array_merge( $return, $this->discounts()->get()->all() ) ])
                ->through(static::$discounts_after)
                ->then(function($data) {
                    list($object, $allDiscounts) = $data;
                    return $allDiscounts;
                });
        }
    }
