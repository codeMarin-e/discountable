<?php
    namespace Marinar\Discountable;

    use Marinar\Discountable\Database\Seeders\MarinarDiscountableInstallSeeder;

    class MarinarDiscountable {

        public static function getPackageMainDir() {
            return __DIR__;
        }

        public static function injects() {
            return MarinarDiscountableInstallSeeder::class;
        }
    }
