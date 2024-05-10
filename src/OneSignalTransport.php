<?php

namespace Sineld\OneSignalMail;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mailer\Transport\AbstractTransport;

class OneSignalTransport extends AbstractTransport
{
    protected string $apiUrl;

    protected string $apiKey;

    protected string $appId;

    public function __construct(string $apiUrl, string $apiKey, string $appId)
    {
        parent::__construct();

        $this->apiUrl = $apiUrl;

        $this->apiKey = $apiKey;

        $this->appId = $appId;
    }

    public function __toString(): string
    {
        return 'onesignal-mail';
    }

    /**
     * @throws Exception
     */
    public function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $response = Http::withHeaders([
            'Content-Type' => 'application/json;charset=utf-8',
            'Authorization' => 'Basic '.$this->apiKey,
        ])->post($this->apiUrl, [
            'app_id' => $this->appId,
            'email_subject' => $email->getSubject(),
            'email_body' => $email->getHtmlBody(),
            'email_from_name' => $email->getFrom()[0]->getName(),
            'email_from_address' => $email->getFrom()[0]->getAddress(),
            'email_reply_to_address' => $this->getEmailAddresses($email, 'getReplyTo'),
            'email_preheader' => Str::limit($email->getTextBody(), 64),
            'include_email_tokens' => [$this->getEmailAddresses($email)],
            'include_unsubscribed' => true,
        ]);

        if($response->failed()){
            throw new Exception('OneSignal Email failed. Error: '. $response->body());
        }
    }

    protected function getEmailAddresses(Email $email, string $method = 'getTo'): string
    {
        $data = call_user_func([$email, $method]);

        $addresses = [];
        if (is_array($data)) {
            foreach ($data as $address) {
                $addresses[] = $address->getAddress();
            }
        }

        return implode(',', $addresses);
    }
}
