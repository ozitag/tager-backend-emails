<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

use Illuminate\Mail\Message;
use Ozerich\FileStorage\Models\File;
use Ozerich\FileStorage\Services\TempFile;

class TagerMailAttachments
{
    private $items = [];

    public function getItems()
    {
        return $this->items;
    }

    public function add($path, $as = null, $mime = null, $url = null)
    {
        $this->items[] = [
            'path' => $path,
            'as' => $as,
            'mime' => $mime,
            'url' => $url
        ];
    }

    public function setFilePath(int $attachmentIndex, string $filePath)
    {
        if (isset($this->items[$attachmentIndex])) {
            $this->items[$attachmentIndex]['path'] = $filePath;
        }
    }

    public function addFile(File $file, ?string $filename = null): void
    {
        $this->add($file->getLocalPath(), $filename ?: $file->name, $file->mime, $file->getUrl());
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
            if (empty($item['path'])) continue;

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
