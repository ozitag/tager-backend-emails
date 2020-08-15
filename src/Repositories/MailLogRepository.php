<?php

namespace OZiTAG\Tager\Backend\Mail\Repositories;

use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;
use OZiTAG\Tager\Backend\Mail\Enums\TagerMailStatus;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailLog;

class MailLogRepository extends EloquentRepository
{
    public function __construct(TagerMailLog $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $days
     * @return TagerMailLog[]
     */
    public function findSkipForLastDays($days)
    {
        $startDateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d', time() - ($days-1) * 86400)));

        return $this->model
            ->where('created_at', '>=', $startDateTime)
            ->where('status','=',TagerMailStatus::Skip)
            ->get();
    }
}
