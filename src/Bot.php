<?php

namespace TelegramBot;

use GuzzleHttp\Client;

class Bot
{
    private $token;
    private $timewebLogin;
    private $timewebPassword;
    private $timewebAppkey;
    private Client $httpClient;
    private $listDomains;
    private $accessToken;

    public function __construct($token, $timewebLogin, $timewebPassword, $timewebAppkey, $listDomains = '')
    {
        $this->token = $token;
        $this->timewebLogin = $timewebLogin;
        $this->timewebPassword = $timewebPassword;
        $this->timewebAppkey = $timewebAppkey;
        $this->listDomains = $listDomains;
        $this->httpClient = new Client();
        $this->accessToken = null;
    }

    public function handleUpdate($update)
    {
        if (isset($update['message'])) {
            $message = $update['message'];
            $chatId = $message['chat']['id'];
            $text = $message['text'] ?? '';

            if (preg_match('/([a-zA-Z0-9-]+\.[a-zA-Z]{2,})/', $text, $matches)) {
                $domain = $matches[1];
                $expiry = $this->checkDomainExpiry($domain);
                if ($expiry) {
                    $response = "Domain $domain expires on: $expiry";
                } else {
                    $response = "Could not retrieve expiry for $domain";
                }
            } else {
                $response = "Please send a domain name to check expiry.";
            }

            $this->sendMessage($chatId, $response);
        }
    }

    private function checkDomainExpiry($domain)
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = $this->httpClient->get('https://api.timeweb.com/api/v1/domains/' . $domain, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['expiry_date'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function sendMessage($chatId, $text)
    {
        $this->httpClient->post("https://api.telegram.org/bot{$this->token}/sendMessage", [
            'json' => [
                'chat_id' => $chatId,
                'text' => $text,
            ],
        ]);
    }

    private function getAccessToken()
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        try {
            $response = $this->httpClient->post('https://api.timeweb.ru/v1.2/access', [
                'json' => [
                    'login' => $this->timewebLogin,
                    'password' => $this->timewebPassword,
                    'appkey' => $this->timewebAppkey,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $this->accessToken = $data['access_token'] ?? null;
            return $this->accessToken;
        } catch (\Exception $e) {
            return null;
        }
    }
}
?>