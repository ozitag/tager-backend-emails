<?php

namespace OZiTAG\Tager\Backend\Mail\Jobs;

use OZiTAG\Tager\Backend\Core\QueueJob;
use OZiTAG\Tager\Backend\Mail\Enums\TagerMailStatus;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailLog;
use App\Models\Product;
use App\Repositories\Interfaces\IProductReviewRepository;
use OZiTAG\Tager\Backend\Mail\Repositories\MailLogRepository;

class ProcessSendingRealMailJob extends QueueJob
{
    /** @var string */
    private $to;

    /** @var string */
    private $subject;

    /** @var string */
    private $body;

    /** @var integer */
    private $logId;

    public function __construct($to, $subject, $body, $logId)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->logId = $logId;
    }

    public function handle(MailLogRepository $mailLogRepository)
    {
        $logModel = $mailLogRepository->find($this->logId);
        if (!$logModel) {
            return;
        }

        $logModel->status = TagerMailStatus::Sending;
        $logModel->save();
    }
}
