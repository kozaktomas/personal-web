<?php declare(strict_types=1);

namespace Kozak\Tomas\App\Model;

final class Mailer
{

    private const
        MESSAGE_BOT_NAME = 'kozak.in BOT',
        MESSAGE_BODY = "__**NEW MESSAGE - %s**__\n**Name:** %s\n**Email:** %s\n\n%s";

    private string $webhookUrl;

    public function __construct(string $webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * @throws MailerException
     */
    public function sendMessage(string $name, string $email, string $content): void
    {
        $payload = $this->buildMessage($name, $email, $content);

        $ch = curl_init($this->webhookUrl);
        if ($ch === false) {
            throw new MailerException('could not create curl object');
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code > 299) {
            throw new MailerException('could not send message');
        }
    }

    /**
     * @throws MailerException
     */
    private function buildMessage(string $name, string $email, string $content): string
    {
        $now = (new \DateTime())->format('Y-m-d H:i');
        $msg = [
            'username' => self::MESSAGE_BOT_NAME,
            'content' => sprintf(self::MESSAGE_BODY, $now, $name, $email, $content),
        ];

        $json = json_encode($msg);
        if ($json === false) {
            throw new MailerException('could not encode json with message');
        }

        return $json;
    }
}
