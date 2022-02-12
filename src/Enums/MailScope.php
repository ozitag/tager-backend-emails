<?php

namespace OZiTAG\Tager\Backend\Mail\Enums;

enum MailScope:string
{
    case ViewTemplates = 'mail.view-templates';
    case EditTemplates = 'mail.edit-templates';
    case ViewLogs = 'mail.view-logs';
}
