<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationSetting extends Model
{
    protected $fillable = [
        'enabled',
        'last_import_date',
        'last_signal_date',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'last_import_date' => 'date',
        'last_signal_date' => 'date',
    ];
}
