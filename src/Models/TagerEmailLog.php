<?php

namespace OZiTAG\Tager\Backend\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ozerich\FileStorage\Models\File;

class TagerEmailLog extends Model
{
    protected $table = 'tager_email_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template',
        'email',
        'subject',
        'body',
        'status',
        'debug',
        'error',
    ];
}
