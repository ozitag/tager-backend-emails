<?php

namespace OZiTAG\Tager\Backend\Seo\Models;

use Illuminate\Database\Eloquent\Model;

class TagerMailTemplate extends Model
{
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
        'recipients'
    ];
}
