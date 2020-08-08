<?php

namespace OZiTAG\Tager\Backend\Mail\Services;

use OZiTAG\Tager\Backend\Mail\Utils\TagerMailAttachments;

class MandrillService implements ITagerMailService
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function sendUsingTemplate($to, $template, $templateParams = null, ?TagerMailAttachments $attachments = null)
    {
        return true;
    }
}
