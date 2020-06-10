<?php

namespace OZiTAG\Tager\Backend\Mail\Jobs;

use OZiTAG\Tager\Backend\Core\QueueJob;
use OZiTAG\Tager\Backend\Mail\Enums\TagerMailStatus;
use OZiTAG\Tager\Backend\Mail\Exceptions\TagerMailSenderException;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailLog;
use App\Models\Product;
use App\Repositories\Interfaces\IProductReviewRepository;
use OZiTAG\Tager\Backend\Mail\Repositories\MailLogRepository;
use OZiTAG\Tager\Backend\Mail\Senders\SenderFactory;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailConfig;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailSender;

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
