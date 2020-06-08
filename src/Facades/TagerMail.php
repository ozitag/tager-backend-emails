<?php

namespace OZiTAG\Tager\Backend\Mail\Facades;

use Illuminate\Support\Facades\Facade;

class TagerMail extends Facade{

    protected static function getFacadeAccessor(){

        return 'tager-mail';
    }
}
