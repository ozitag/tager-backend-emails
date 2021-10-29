<?php

namespace OZiTAG\Tager\Backend\Mail\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OZiTAG\Tager\Backend\Core\Models\TModel;

/**
 * Class TagerMailTemplate
 * @package OZiTAG\Tager\Backend\Mail\Models
 *
 * @property string $name
 * @property string $value
 * @property string $subject
 * @property string $recipients
 * @property string $cc
 * @property string $bcc
 * @property string $body
 * @property string $template
 * @property string $service_template
 * @property boolean $changed_by_admin
 * @property string $from_email
 * @property string $from_name
 */
class TagerMailTemplate extends TModel
{
    use SoftDeletes;

    protected $table = 'tager_mail_templates';

    static $defaultOrder = 'priority ASC';

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
        'cc', 'bcc',
        'body',
        'template',
        'service_template',
        'changed_by_admin',
        'from_email', 'from_name'
    ];
}
