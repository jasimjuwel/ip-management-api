<?php

namespace App\Models;

use Carbon\Carbon;
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
     * @return string
     */
    public function getLogTimeAttribute():string
    {
        return dateTimeConvertDBtoForm($this->attributes['log_time']);
    }
}
