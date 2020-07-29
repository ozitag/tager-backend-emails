<?php

namespace OZiTAG\Tager\Backend\Mail\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailConfig;

class MailTemplateResource extends JsonResource
{
    private $config;

    public function __construct($resource)
    {
        $this->config = new TagerMailConfig();
        parent::__construct($resource);
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'alias' => (string)$this->template,
            'name' => (string)$this->name,
            'subject' => (string)$this->subject,
            'body' => (string)$this->body,
            'recipients' => $this->recipients ? explode(',', $this->recipients) : [],
            'variables' => $this->config->getTemplateVariables($this->template)
        ];
    }
}
