<?php

namespace OZiTAG\Tager\Backend\Mail\Enums;

use OZiTAG\Tager\Backend\Core\Enums\Enum;

final class TagerMailStatus extends Enum
{
    const Disabled = 'DISABLED';
    const Created = 'CREATED';
    const Sending = 'SENDING';
    const Skip = 'SKIP';
    const Failure = 'FAILURE';
    const Success = 'SUCCESS';

    public static function label(?string $value): string
    {
        switch ($value) {
            case self::Disabled:
                return __('tager-mail::statuses.disabled');
            case self::Created:
                return __('tager-mail::statuses.created');
            case self::Sending:
                return __('tager-mail::statuses.sending');
            case self::Skip:
                return __('tager-mail::statuses.skip');
            case self::Failure:
                return __('tager-mail::statuses.failure');
            case self::Success:
                return __('tager-mail::statuses.success');
            default:
                return parent::label($value);
        }
    }
}
