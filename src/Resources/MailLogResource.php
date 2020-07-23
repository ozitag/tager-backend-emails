<?php

namespace OZiTAG\Tager\Backend\Mail\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MailLogResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'template' => $this->template ? $this->template->name : null,
            'recipient' => $this->recipient,
            'subject' => $this->subject,
            'body' => $this->body,
            'isDebug' => (boolean)$this->debug,
            'status' => $this->status,
            'error' => $this->error,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
