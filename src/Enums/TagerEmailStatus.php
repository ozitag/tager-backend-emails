<?php

namespace OZiTAG\Tager\Backend\Enums;

use OZiTAG\Tager\Backend\Core\Enum;

final class TagerEmailStatus extends Enum
{
    const Created = 'CREATED';
    const Sending = 'SENDING';
    const Failure = 'FAILURE';
    const Success = 'SUCCESS';
}
