<?php
    namespace Marinar\Discountable\Database\Seeders;

    use Illuminate\Database\Seeder;
    use Marinar\Discountable\MarinarDiscountable;

    class MarinarDiscountableInstallSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_discountable';
            static::$packageDir = MarinarDiscountable::getPackageMainDir();
        }

        public function run() {
            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoInstall();

            $this->refComponents->info("Done!");
        }

    }
