<?php

namespace OZiTAG\Tager\Backend\Mail\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TagerMailTemplate
 * @package OZiTAG\Tager\Backend\Mail\Models
 * 
 * @property string $name
 * @property string $value
 * @property string $subject
 * @property string $recipients
 * @property string $body
 * @property string $template
 * @property string $service_template
 * @property boolean $changed_by_admin
 */
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
        'changed_by_admin'
    ];
}
