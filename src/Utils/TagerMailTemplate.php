<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

class TagerMailTemplate
{
    private $body;

    private $subject;

    private $recipients = [];

    private $template = null;

    private $databaseId = null;

    private $serviceTemplate = null;

    public function setBody($value)
    {
        $this->body = $value;
    }

    public function setSubject($value)
    {
        $this->subject = $value;
    }

    public function setRecipients($value)
    {
        $this->recipients = $value;
    }

    public function setTemplate($template, $databaseId = null, $serviceTemplate = null)
    {
        $this->template = $template;
        $this->databaseId = $databaseId;
        $this->serviceTemplate = $serviceTemplate;
    }

    public function getServiceTemplate()
    {
        return $this->serviceTemplate;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getTemplateName()
    {
        return $this->template;
    }

    public function getDatabaseId()
    {
        return $this->databaseId;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }
}
