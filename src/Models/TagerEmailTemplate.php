<?php

namespace OZiTAG\Tager\Backend\Seo\Models;

use Illuminate\Database\Eloquent\Model;

class TagerEmailTemplate extends Model
{
    protected $table = 'tager_email_templates';

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
