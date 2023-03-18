<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'log_time', 'request_path', 'request_id', 'post_json', 'final_response', 'request_ip'
    ];

    /**
     * get log date time format.
     *
     * @return date
     */
    public function getLogTimeAttribute()
    {
        return dateTimeConvertDBtoForm($this->attributes['log_time']);
    }
}
