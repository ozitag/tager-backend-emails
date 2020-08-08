<?php

namespace OZiTAG\Tager\Backend\Mail\Services;

use OZiTAG\Tager\Backend\Mail\Utils\TagerMailAttachments;

class MandrillService implements ITagerMailService
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    private function httpRequest($endpoint, $params = [])
    {
        $params = array_merge(['key' => $this->apiKey], $params);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 600);
        curl_setopt($curl, CURLOPT_URL, 'https://mandrillapp.com/api/1.0/' . $endpoint . '.json');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));

        curl_setopt($curl, CURLOPT_VERBOSE, false);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * @param array $templateParams
     * @return array
     */
    private function getTemplateContent($templateParams)
    {
        $templateContent = [];
        if (is_array($templateParams)) {
            foreach ($templateParams as $param => $value) {
                $templateContent[] = [
                    'name' => $param,
                    'content' => $value
                ];
            }
        }
        return $templateContent;
    }

    /**
     * @param TagerMailAttachments|null $attachments
     * @return array
     */
    private function getAttachments(?TagerMailAttachments $attachments)
    {
        $result = [];

        if (!$attachments) {
            return [];
        }

        foreach ($attachments->getItems() as $item) {
            $path = $item['path'];
            if (!is_file($path)) {
                continue;
            }

            $f = fopen($path, 'a+');
            $fileContent = fread($f, filesize($path));
            fclose($f);

            $result[] = [
                'type' => $item['mime'],
                'name' => $item['as'],
                'content' => base64_encode($fileContent)
            ];
        }

        return $result;
    }

    /**
     * @param string $to
     * @param string $template
     * @param array|null $templateParams
     * @param string|null $subject
     * @param TagerMailAttachments|null $attachments
     */
    public function sendUsingTemplate($to, $template, $templateParams = null, $subject = null, ?TagerMailAttachments $attachments = null)
    {
        $templateContent = [];
        if (is_array($templateParams)) {
            foreach ($templateParams as $param => $value) {
                $templateContent[] = [
                    'name' => $param,
                    'content' => $value
                ];
            }
        }

        $this->httpRequest('messages/send-template', [
            'template_name' => $template,
            'template_content' => $this->getTemplateContent($templateParams),
            'message' => [
                'to' => [
                    [
                        'email' => $to
                    ]
                ],
                'subject' => $subject,
                'merge' => true,
                'merge_language' => 'handlebars',
                'global_merge_vars' => $this->getTemplateContent($templateParams),
                'attachments' => $this->getAttachments($attachments)
            ]
        ]);
    }

    /**
     * @return array
     */
    public function getTemplates()
    {
        $items = $this->httpRequest('templates/list');

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'value' => $item['slug'],
                'label' => $item['name']
            ];
        }

        return $result;
    }
}
