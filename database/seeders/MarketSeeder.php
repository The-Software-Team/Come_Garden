<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Market\Listing;
use App\Models\Market\Question;
use App\Models\Member;

class MarketSeeder extends Seeder
{
    public function run(): void
    {
        $user = Member::first() ?? Member::factory()->create();

        // ─── Listings (STATIC) ───
        $listings = [
            [
                'produce_name' => 'Tomatoes',
                'type' => 'standard',
                'quantity_kg' => 5,
                'price' => 20,
                'pickup_location' => 'Farm Gate A',
            ],
            [
                'produce_name' => 'Potatoes',
                'type' => 'flash',
                'quantity_kg' => 10,
                'price' => 15,
                'pickup_window_hours' => 3,
                'pickup_location' => 'Storage Unit 2',
            ],
            [
                'produce_name' => 'Cucumbers',
                'type' => 'gift',
                'quantity_kg' => 3,
                'price' => 0,
                'pickup_location' => 'Community Garden',
            ],
            [
                'produce_name' => 'Carrots',
                'type' => 'standard',
                'quantity_kg' => 4,
                'price' => 12,
                'pickup_location' => 'Plot 7',
            ],
            [
                'produce_name' => 'Zucchini',
                'type' => 'standard',
                'quantity_kg' => 6,
                'price' => 18,
                'pickup_location' => 'Market Shed',
            ],
        ];

        foreach ($listings as $data) {
            Listing::updateOrCreate(
                ['produce_name' => $data['produce_name']],
                array_merge($data, [
                    'user_id' => $user->id,
                    'description' => 'Fresh and locally grown.',
                    'expires_at' => now()->addDays(2),
                    'status' => 'available',
                    'quality_score' => 4.5,
                ])
            );
        }

        // ─── Questions (STATIC) ───
        $questions = [
            [
                'title' => 'How to prevent blossom end rot?',
                'body' => 'My tomatoes keep getting black spots underneath.',
            ],
            [
                'title' => 'Best way to store potatoes?',
                'body' => 'They sprout too quickly in my kitchen.',
            ],
            [
                'title' => 'Why are my cucumbers bitter?',
                'body' => 'Taste is off even though they look fine.',
            ],
            [
                'title' => 'How often should I water carrots?',
                'body' => 'Soil dries fast in my area.',
            ],
            [
                'title' => 'Zucchini growing too fast?',
                'body' => 'They get huge overnight.',
            ],
        ];

        foreach ($questions as $q) {
            Question::updateOrCreate(
                ['title' => $q['title']],
                [
                    'user_id' => $user->id,
                    'body' => $q['body'],
                    'is_resolved' => false,
                ]
            );
        }
    }
}