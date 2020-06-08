<?php

namespace OZiTAG\Tager\Backend\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ozerich\FileStorage\Models\File;

class TagerEmailTemplate extends Model
{
    protected $table = 'tager_email_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'value', 'subject', 'recipients'
    ];
}
