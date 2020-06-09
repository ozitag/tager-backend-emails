<?php

namespace OZiTAG\Tager\Backend\Mail\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Ozerich\FileStorage\Models\File;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailTemplate;
use OZiTAG\Tager\Backend\Seo\Models\SeoPage;

class MailTemplateResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'template' => $this->template,
            'name' => $this->name,
            'subject' => $this->subject,
            'body' => $this->body
        ];
    }
}
