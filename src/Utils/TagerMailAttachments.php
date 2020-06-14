<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Ozerich\FileStorage\Models\File;
use OZiTAG\Tager\Backend\Core\Controller;
use OZiTAG\Tager\Backend\Core\SuccessResource;
use OZiTAG\Tager\Backend\Admin\Resources\ProfileResource;

class TagerMailAttachments
{
    private $items = [];

    public function getItems()
    {
        return $this->items;
    }

    public function add($path, $as = null, $mime = null)
    {
        $this->items[] = [
            'path' => $path,
            'as' => $as,
            'mime' => $mime
        ];
    }

    public function addFile(File $file, $filename = null)
    {
        $this->add($file->getPath(), $filename ? $filename : $file->name, $file->mime);
    }

    /**
     * @return false|string
     */
    public function getLogString()
    {
        return json_encode($this->items);
    }

    public function injectToMessage(Message $message)
    {
        foreach ($this->items as $item) {
            $options = [];

            if (!empty($item['as'])) {
                $options['as'] = $item['as'];
            }

            if (!empty($item['mime'])) {
                $options['mime'] = $item['mime'];
            }

            $message->attach($item['path']);
        }
    }
}
