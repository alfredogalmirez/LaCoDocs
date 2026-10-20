<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Topic;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $mainTopic = Topic::create([
            'name' => 'Backend Development',
            'slug' => 'backend-development',
            'parent_id' => null,
        ]);

        $subTopic = Topic::create([
            'name' => 'Controllers',
            'slug' => 'controllers',
            'parent_id' => $mainTopic->id,
        ]);

        Document::create([
            'title' => 'How to write a Controller',
            'slug' => 'how-to-write-a-controller',
            'topic_id' => $subTopic->id,
            'content_blocks' => ['text' => 'this is a document content.'],
        ]);

    }
}
