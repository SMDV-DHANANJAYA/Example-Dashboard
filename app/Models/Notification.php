<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    const DANGER = 1;
    const WARNING = 2;
    const SUCCESS = 3;

    const NOTIFICATION_NEW = 0;
    const NOTIFICATION_READ = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'text',
        'state'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i A',
    ];
}
