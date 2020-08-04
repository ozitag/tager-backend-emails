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
}
