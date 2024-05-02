<?php

namespace Database\Seeders;

use App\Models\MatterType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MatterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $matter_types = [
            'Matter Type 01' => [
                'Matter 01 Sub Type Type 1',
                'Matter 01 Sub Type Type 2',
                'Matter 01 Sub Type Type 3'
            ],
            'Matter Type 02' => [
                'Matter 02 Sub Type Type 1',
                'Matter 02 Sub Type Type 2',
                'Matter 02 Sub Type Type 3'
                ],
            'Matter Type 03' => [
                'Matter 03 Sub Type Type 1',
                'Matter 03 Sub Type Type 2',
                'Matter 03 Sub Type Type 3'
            ]
            ];

        foreach ($matter_types as $matter_type => $subtypes) {
            $type = MatterType::updateOrCreate(['name' => $matter_type], [
                'key' => Str::slug($matter_type),
                'description' => $matter_type,
                'status' => true
            ]);

            foreach ($subtypes as $subtype) {
                $type->matter_sub_types()->updateOrCreate(['name' => $subtype], [
                    'key' => Str::slug($subtype),
                    'description' => $subtype,
                    'status' => true
                ]);
            }
        }

    }
}
