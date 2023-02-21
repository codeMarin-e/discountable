<?php
namespace Marinar\Discountable\Database\Seeders;

use Illuminate\Database\Seeder;
use Marinar\Discountable\MarinarDiscountable;
use App\Models\Discount;

class MarinarDiscountableRemoveSeeder extends Seeder {

    use \Marinar\Marinar\Traits\MarinarSeedersTrait;

    public static function configure() {
        static::$packageName = 'marinar_discountable';
        static::$packageDir = MarinarDiscountable::getPackageMainDir();
    }

    public function run() {
        if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

        $this->autoRemove();

        $this->refComponents->info("Done!");
    }

    public function clearMe() {
        $this->refComponents->task("Clear DB", function() {
            foreach(Discount::get() as $discount) {
                $discount->delete();
            }
            return true;
        });
    }
}
