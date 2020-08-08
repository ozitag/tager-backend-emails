<?php

namespace OZiTAG\Tager\Backend\Mail\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MailLogResource extends JsonResource
{
    public function toArray($request)
    {
        $attachments = [];

        if ($this->attachments) {
            $attachmentsJson = json_decode($this->attachments, true);
            if ($attachmentsJson) {
                foreach ($attachmentsJson as $attachment) {
                    $attachments[] = [
                        'name' => $attachment['as'] ?? null,
                        'url' => $attachment['url'] ?? null
                    ];
                }
            }
        }

        return [
            'id' => $this->id,
            'template' => $this->template,
            'serviceTemplate' => $this->service_template,
            'recipient' => $this->recipient,
            'subject' => $this->subject,
            'body' => $this->body ? $this->body : $this->service_template_params,
            'status' => $this->status,
            'error' => $this->error,
            'attachments' => $attachments,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
