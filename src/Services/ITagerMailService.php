<?php

namespace OZiTAG\Tager\Backend\Mail\Services;

use OZiTAG\Tager\Backend\Mail\Utils\TagerMailAttachments;

interface ITagerMailService
{
    public function sendUsingTemplate(string $to, string $cc, string $bcc, string $template, ?array $templateParams = null, ?string $subject = null, ?TagerMailAttachments $attachments = null, ?string $fromEmail = null, ?string $fromName = null);

    public function getTemplates();
}
