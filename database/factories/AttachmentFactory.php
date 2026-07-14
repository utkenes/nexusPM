<?php

namespace Database\Factories;

use App\Models\Attachment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attachment>
 */
class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'attachable_type' => Task::class,
            'attachable_id' => Task::factory(),
            'file_path' => 'attachments/dummy.pdf',
            'file_name' => 'dummy.pdf',
            'file_size' => 1024,
            'mime_type' => 'application/pdf',
        ];
    }
}
