<?php

namespace OZiTAG\Tager\Backend\Mail\Transports;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Arr;
use SendGrid;
use SendGrid\Mail\Mail;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;

class TransportFactory
{
    private static function httpClient(array $config): HttpClient
    {
        return new HttpClient(Arr::add(
            $config['guzzle'] ?? [], 'connect_timeout', 60
        ));
    }

    public static function mandrill(array $config): MandrillTransport
    {
        return new MandrillTransport(self::httpClient($config), $config['secret']);
    }

    public static function sendgrid(array $config): SendGridTransport
    {
        $email = new Mail();
        $sendgrid = new SendGrid($config['api_key']);

        return new SendGridTransport($email, $sendgrid);
    }

    public static function sendinblue(array $config): SendinblueTransport
    {
        $httpClient = self::httpClient($config);

        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $config['key']);
        $transactionalEmailsApi = new TransactionalEmailsApi($httpClient, $config);
        $sendSmtpEmail = new SendSmtpEmail();

        return new SendinblueTransport($transactionalEmailsApi, $sendSmtpEmail);
    }

    public static function sendpulse(array $config): SendPulseTransport
    {
        $SPApiClient = new ApiClient(
            $config['user_id'],
            $config['user_secret'],
            new FileStorage(storage_path('/'))
        );

        return new SendPulseTransport($SPApiClient);
    }
}
