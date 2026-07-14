<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Carbon\Carbon;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $organization_id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property ProjectStatus $status
 * @property Carbon|null $start_date
 * @property Carbon|null $due_date
 * @property int $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property Organization $organization
 * @property Collection<int, User> $members
 * @property User $creator
 * @property User|null $updater
 * @property Collection<int, Task> $tasks
 */
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory, LogsActivity, SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    protected $fillable = [
        'organization_id',
        'title',
        'slug',
        'description',
        'status',
        'start_date',
        'due_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
            'start_date' => 'date',
            'due_date' => 'date',
        ];
    }

    /**
     * The organization this project belongs to.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * The members associated with this project.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * The user who created the project.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The user who last updated the project.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * The tasks under this project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
