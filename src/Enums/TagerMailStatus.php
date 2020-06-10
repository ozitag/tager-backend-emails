<?php

namespace OZiTAG\Tager\Backend\Mail\Enums;

use OZiTAG\Tager\Backend\Core\Enum;

final class TagerMailStatus extends Enum
{
    const Created = 'CREATED';
    const Sending = 'SENDING';
    const Failure = 'FAILURE';
    const Success = 'SUCCESS';
}
