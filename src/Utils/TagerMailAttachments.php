<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

use Illuminate\Mail\Message;
use Ozerich\FileStorage\Models\File;

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

            $message->attach($item['path'], $options);
        }
    }
}
