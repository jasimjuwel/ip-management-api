<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpTable extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ip_address', 'ip_label', 'created_by', 'updated_by'
    ];

    /**
     * get created date time format.
     *
     * @return string
     */
    public function getCreatedAtAttribute(): string
    {
        return dateTimeConvertDBtoForm($this->attributes['created_at']);
    }
}
