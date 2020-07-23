<?php

namespace OZiTAG\Tager\Backend\Mail\Jobs;

use OZiTAG\Tager\Backend\Mail\Repositories\MailLogRepository;

class SetLogStatusJob
{
    /** @var integer */
    private $itemId;

    /** @var string */
    private $status;

    public function __construct($logItemId, $status)
    {
        $this->itemId = $logItemId;
        $this->status = $status;
    }

    public function handle(MailLogRepository $repository)
    {
        $item = $repository->find($this->itemId);
        
        if ($item) {
            $item->status = $this->status;
            $item->save();
        }
    }
}
