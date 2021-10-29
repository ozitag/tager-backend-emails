<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

class TagerMailTemplate
{
    private $body;

    private $subject;

    private $recipients = [];

    private $cc = [];

    private $bcc = [];

    private $template = null;

    private $databaseId = null;

    private $serviceTemplate = null;

    private $fromName = null;

    private $fromEmail = null;

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

    public function setCc($value)
    {
        $this->cc = $value;
    }

    public function setBcc($value)
    {
        $this->bcc = $value;
    }

    public function setTemplate($template, $databaseId = null, $serviceTemplate = null)
    {
        $this->template = $template;
        $this->databaseId = $databaseId;
        $this->serviceTemplate = $serviceTemplate;
    }

    public function setFrom($fromName, $fromEmail)
    {
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
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

    public function getCc()
    {
        return $this->cc;
    }

    public function getBcc()
    {
        return $this->bcc;
    }

    public function getFromName(){
        return $this->fromName;
    }

    public function getFromEmail(){
        return $this->fromEmail;
    }
}
