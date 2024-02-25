<?php

namespace App\Models;

use App\Enums\DemoTestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemoTest extends Model
{
    use HasFactory;

    protected $table = 'demo_test';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'ref', 'description', 'status', 'is_active'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => DemoTestStatus::class,
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active records by ref.
     */
    public function scopeInactiveRef($query, $ref)
    {
        return $query->where('ref', $ref)->where('is_active', false);
    }
}
