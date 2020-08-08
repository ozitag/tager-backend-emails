<?php

namespace OZiTAG\Tager\Backend\Mail\Controllers;

use OZiTAG\Tager\Backend\Core\Controllers\Controller;
use OZiTAG\Tager\Backend\Mail\Features\ListMailLogsFeature;

class AdminMailLogsController extends Controller
{
    public function index()
    {
        return $this->serve(ListMailLogsFeature::class);
    }
}
