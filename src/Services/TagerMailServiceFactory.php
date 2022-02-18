<?php

namespace OZiTAG\Tager\Backend\Mail\Services;

use OZiTAG\Tager\Backend\Mail\Exceptions\TagerMailInvalidServiceConfigException;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailConfig;

class TagerMailServiceFactory
{
    /**
     * @return ITagerMailService
     */
    public static function create()
    {
        $mailer = TagerMailConfig::getService();

        if ($mailer == 'mandrill') {
            $apiKey = TagerMailConfig::getMandrillSecret();

            if (empty($apiKey)) {
                throw new TagerMailInvalidServiceConfigException('Mandrill API Key is not set');
            }

            return new MandrillService($apiKey);
        }

        return null;
    }
}
