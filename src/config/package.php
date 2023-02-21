<?php
	return [
		'install' => [
            'php artisan db:seed --class="\Marinar\Discountable\Database\Seeders\MarinarDiscountableInstallSeeder"',
		],
        'remove' => [
            'php artisan db:seed --class="\Marinar\Discountable\Database\Seeders\MarinarDiscountableRemoveSeeder"',
        ]
	];
