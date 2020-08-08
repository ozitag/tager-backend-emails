<?php

namespace OZiTAG\Tager\Backend\Mail\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagerMailTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'tager_mail_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'value',
        'subject',
        'recipients',
        'body',
        'template',
        'service_template',
    ];
}
