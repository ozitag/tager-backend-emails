<?php

namespace OZiTAG\Tager\Backend\Mail\Services;

use OZiTAG\Tager\Backend\Mail\Exceptions\TagerMailInvalidServiceConfigException;

class TagerMailServiceFactory
{
    /**
     * @return ITagerMailService
     */
    public static function create()
    {
        $mailer = config('mail.default');

        if ($mailer == 'mandrill') {
            $apiKey = config('services.mandrill.secret');

            if (empty($apiKey)) {
                throw new TagerMailInvalidServiceConfigException('Secret for Mandrill is not set');
            }

            return new MandrillService($apiKey);
        }

        throw new TagerMailInvalidServiceConfigException('Invalid Mail Service "' . $mailer . '"');
    }
}
