<?php

declare(strict_types=1);

namespace Codediesel\Library\External\EmailHandler;

use Codediesel\Library\External\EmailHandler\ClientApiHandler;

/**
 * Class Sender
 * Handles the preparation and sending of emails.
 */
class Sender
{
    /**
     * @var string $to The recipient's email address.
     */
    private string $to;
    /**
     * @var string $toName The name of the recipient.
     */
    private string $toName;
    /**
     * @var string $from The sender's email address.
     */
    private string $from;

    /**
     * @var string $subject The subject of the email.
     */
    private string $subject;

    /**
     * @var string $body The body content of the email.
     */
    private string $body;

    /**
     * @var string $fromName The name of the sender.
     */
    private string $fromName;

    /**
     * Sets the recipient's email address.
     *
     * @param string $to The recipient's email address.
     * @throws \InvalidArgumentException If the email address is invalid.
     */
    public function setTo(string $to)
    {
        // Validate email address
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email address: $to");
        }
        $this->to = $to;
    }

    /**
     * Sets the sender's email address.
     *
     * @param string $from The sender's email address.
     * @throws \InvalidArgumentException If the email address is invalid.
     */
    public function setFrom(string $from)
    {
        // Validate email address
        if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email address: $from");
        }
        $this->from = $from;
    }

    public function setFromName(string $name)
    {
        // Validate name
        if (empty($name)) {
            throw new \InvalidArgumentException("Name cannot be empty");
        }
        $this->fromName = $name;
    }



    /**
     * Sets the subject of the email.
     *
     * @param string $subject The subject of the email.
     * @throws \InvalidArgumentException If the subject is empty.
     */
    public function setSubject(string $subject)
    {
        // Validate subject
        if (empty($subject)) {
            throw new \InvalidArgumentException("Subject cannot be empty");
        }
        $this->subject = $subject;
    }


    public function setToName(string $name)
    {
        // Validate name
        if (empty($name)) {
            throw new \InvalidArgumentException("Name cannot be empty");
        }
        $this->toName = $name;
    }
    /**
     * Sets the body content of the email.
     *
     * @param string $body The body content of the email.
     * @throws \InvalidArgumentException If the body is empty.
     */
    public function setBody(string $body)
    {
        // Validate body
        if (empty($body)) {
            throw new \InvalidArgumentException("Body cannot be empty");
        }
        $this->body = $body;
    }

    private function messageBody(){
        return [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->from,
                        'Name' => $this->fromName
                    ],
                    'To' => [
                        [
                            'Email' => $this->to,
                            'Name' => $this->toName
                        ]
                    ],
                    'Subject' => $this->subject, 
                    'HTMLPart' => $this->body,
                ]
            ]
        ];
    }
    public function setTemplateID(string $templateID)
    {
        // Validate template ID
        if (empty($templateID)) {
            throw new \InvalidArgumentException("Template ID cannot be empty");
        }
        $this->templateID = $templateID;
    }
    /**
     * @var string $templateID The ID of the email template.
     */
    private string $templateID;

    /**
     * Sends the email using the mail() function.
     *
     * @return void
     * @throws \Exception
     */
    public function send() 
    {
        // Use mail() function to send the email
        $apiHandler = new ClientApiHandler();
        $apiHandler->setUrl('send');
        
        $message = $this->messageBody();
        /*
        if($this->templateID)
            $message['Messages'][0]['TemplateID'] = $this->templateID;*/

 

        return $apiHandler->sendEmail($message);
    }
}
