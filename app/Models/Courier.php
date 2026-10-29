<?php

namespace App\Models;

use Database\Factories\CourierFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    /** @use HasFactory<CourierFactory> */
    use HasFactory;

    public const LEVEL_MIN = 1;

    public const LEVEL_MAX = 5;

    public const VEHICLE_TYPES = ['motorcycle', 'car', 'van', 'truck'];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'level',
        'vehicle_type',
        'vehicle_plate',
        'city',
        'address',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Match every search term against the courier name.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        $terms = preg_split('/\s+/', trim((string) $search), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        foreach ($terms as $term) {
            $query->where('name', 'like', "%{$term}%");
        }

        return $query;
    }

    /**
     * Filter by one or more courier levels.
     */
    public function scopeFilterLevel(Builder $query, array|string|null $levels): Builder
    {
        $levels = is_array($levels) ? $levels : explode(',', (string) $levels);
        $levels = array_filter(
            array_map('intval', $levels),
            fn (int $level): bool => $level >= self::LEVEL_MIN && $level <= self::LEVEL_MAX,
        );

        return $levels === [] ? $query : $query->whereIn('level', $levels);
    }

    /**
     * Sort by a whitelisted column. Prefix with "-" for descending order.
     */
    public function scopeSort(Builder $query, ?string $sort): Builder
    {
        $sort = $sort ?: 'name';
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');

        if (! in_array($column, ['name', 'level', 'created_at'], true)) {
            [$column, $direction] = ['name', 'asc'];
        }

        return $query->orderBy($column, $direction)->orderBy('id');
    }
}
