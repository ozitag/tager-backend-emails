<?php

namespace OZiTAG\Tager\Backend\Mail\Services;

use OZiTAG\Tager\Backend\Mail\Utils\TagerMailAttachments;

interface ITagerMailService
{
   public function sendUsingTemplate($to, $template, $templateParams = null, ?TagerMailAttachments $attachments = null);
}
