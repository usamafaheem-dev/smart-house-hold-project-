<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cleaning',
                'icon' => 'fa-broom',
                'description' => 'Deep house cleaning, sofa wash, carpet cleaning, and kitchen sanitation services.',
                'estimated_price' => 2500.00,
            ],
            [
                'name' => 'Plumbing',
                'icon' => 'fa-faucet',
                'description' => 'Water pipe leak repairs, tap fitting, motor installation, and bathroom plumbing.',
                'estimated_price' => 1500.00,
            ],
            [
                'name' => 'Electrician',
                'icon' => 'fa-bolt',
                'description' => 'Short circuit fixing, fan installation, wiring, breaker replacement, and light fitting.',
                'estimated_price' => 1800.00,
            ],
            [
                'name' => 'Gardening',
                'icon' => 'fa-seedling',
                'description' => 'Lawn maintenance, plant trimming, soil fertilization, and garden design.',
                'estimated_price' => 2000.00,
            ],
            [
                'name' => 'Appliance Repair',
                'icon' => 'fa-tv',
                'description' => 'AC servicing, refrigerator repair, washing machine maintenance, and microwave fix.',
                'estimated_price' => 3000.00,
            ],
        ];

        foreach ($categories as $cat) {
            ServiceCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
