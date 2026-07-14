<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $organization_id
 * @property string $name
 * @property string $color
 * @property Organization $organization
 * @property Collection<int, Task> $tasks
 */
class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'color',
    ];

    /**
     * Get the organization that owns the label.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the tasks associated with the label.
     */
    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }
}
