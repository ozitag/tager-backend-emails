<?php

namespace OZiTAG\Tager\Backend\Mail\Models;

use OZiTAG\Tager\Backend\Core\Models\TModel;

/**
 * Class TagerMailLog
 * @package OZiTAG\Tager\Backend\Mail\Models
 *
 * @property int $template_id
 * @property string $recipient
 * @property string $subject
 * @property string $body
 * @property string $from_email
 * @property string $from_name
 * @property string $status
 * @property string $template
 * @property string $error
 * @property string $service_template
 * @property string $service_template_params
 *
 */
class TagerMailLog extends TModel
{
    protected $table = 'tager_mail_logs';

    static $defaultOrder = 'created_at desc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id',
        'recipient', 'cc', 'bcc',
        'subject',
        'body',
        'from_email',
        'from_name',
        'status',
        'template',
        'error',
        'attachments',
        'service_template',
        'service_template_params',
    ];

    public function template()
    {
        return $this->belongsTo(TagerMailTemplate::class, );
    }
}
