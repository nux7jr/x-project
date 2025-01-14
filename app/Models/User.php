<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\HistoryPoints;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;
    protected $fillable = [
        'username',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'username' => 'string',
        ];
    }
    /**
     * Получить историю очков
     */
    public function pointsHistory(): HasMany
    {
        return $this->hasMany(HistoryPoints::class);
    }
}
