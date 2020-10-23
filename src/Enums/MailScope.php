<?php

namespace OZiTAG\Tager\Backend\Mail\Enums;

use OZiTAG\Tager\Backend\Core\Enums\Enum;

final class MailScope extends Enum
{
    const ViewTemplates = 'mail.view-templates';
    const EditTemplates = 'mail.edit-templates';
    const ViewLogs = 'mail.view-logs';
}
