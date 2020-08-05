<?php

namespace OZiTAG\Tager\Backend\Mail\Events;

use Illuminate\Mail\Events\MessageSent;
use OZiTAG\Tager\Backend\Mail\Enums\TagerMailStatus;
use OZiTAG\Tager\Backend\Mail\Jobs\SetLogStatusJob;

class MessageSentHandler
{
    /**
     * Handle the event.
     *
     * @param MessageSent $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $logItemId = $event->data['eventData']['logId'] ?? null;

        if (!$logItemId) {
            return;
        }

        dispatch(new SetLogStatusJob($logItemId, TagerMailStatus::Success));
    }

}
