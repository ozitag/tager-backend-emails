<?php

namespace OZiTAG\Tager\Backend\Mail\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ozerich\FileStorage\Models\File;

class TagerMailLog extends Model
{
    protected $table = 'tager_mail_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id',
        'recipient',
        'subject',
        'body',
        'status',
        'debug',
        'error',
    ];

    public function template()
    {
        return $this->belongsTo(TagerMailTemplate::class, );
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }
}
