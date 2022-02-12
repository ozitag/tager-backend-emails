<?php

namespace OZiTAG\Tager\Backend\Mail\Enums;

enum MailStatus: string
{
    case Disabled = 'DISABLED';
    case Created = 'CREATED';
    case Sending = 'SENDING';
    case Skip = 'SKIP';
    case Failure = 'FAILURE';
    case Success = 'SUCCESS';

    public static function label(?self $value): string
    {
        return match ($value) {
            self::Disabled => __('tager-mail::statuses.disabled'),
            self::Created => __('tager-mail::statuses.created'),
            self::Sending => __('tager-mail::statuses.sending'),
            self::Skip => __('tager-mail::statuses.skip'),
            self::Failure => __('tager-mail::statuses.failure'),
            self::Success => __('tager-mail::statuses.success'),
            default => 'Unknown',
        };
    }
}
