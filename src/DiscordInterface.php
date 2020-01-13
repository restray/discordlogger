<?php

namespace Restray\DiscordLogger;

class DiscordInterface
{
    /**
     * Send a message on the webhook.
     *
     * @param string    $level_name
     * @param string    $message
     * @param array     $context
     * @param string    $date
     *
     * @return void
     */
    public function send(string $level_name, string $message, array $context, string $date)
    {
        $context = reset($context) ?: null;

        $payload = $this->makeEmbed($level_name,
                                    ($context && gettype($context) != 'integer')  ? $context->getMessage() : $message,
                                    ($context && gettype($context) != 'integer')  ? $context->getFile() : null,
                                    $date);

        $this->sendPayload($payload, $this->getWebhookUrl($level_name));
    }

    /**
     * Function to send the payload to discord.
     *
     * @param string $payload
     * @param string $url
     *
     * @return void
     */
    private function sendPayload(string $payload, string $url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $result = curl_exec($ch);

        // Check for errors and display the error message
        if ($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            throw new \Exception("cURL error ({$errno}):\n {$error_message}");
        }

        $json_result = json_decode($result, true);

        if (($httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE)) != 204) {
            throw new \Exception($httpcode.':'.$result);
        }

        curl_close($ch);
    }

    /**
     * Get the config webhook associate to the logging level.
     *
     * @param string $level_name
     *
     * @return string
     */
    private function getWebhookUrl(string $level_name) : string
    {
        return config('discordlogger.webhooks.'.strtolower($level_name)) ?: config('discordlogger.webhook');
    }

    /**
     * Create the payload with the embed.
     *
     * @param string $level_name
     * @param string $error
     * @param string $file
     * @param string $date
     *
     * @return string
     */
    private function makeEmbed(string $level_name, string $error, string $file = null, string $date) : string
    {
        if ($error && ! $file) { 
            $message = "```$error```";
        } elseif ($error && $file) {
            $message = "```$error```\nFile : ```$file```";
        } else {
            $message = '';
        }

        $payload = [
            'embeds' => [
                [
                    'title' => '__Information__',
                    'color' => DiscordEmbedColor::get($level_name),
                    'description' => 'Utilisateur : **' . (\Auth::user() ? \Auth::user()->pseudo : 'Inconnu') . "**\n Date : **$date**",
                    'timestamp' => $date,
                    'url' => url()->current(),
                    'fields' => [
                        [
                            'name' => '__Message__ :',
                            'value' => $message,
                        ],
                        [
                            'name' => '__Environnement__ :',
                            'value' => env('APP_MACHINE', 'Inconnu'),
                        ],
                        [
                            'name' => '__Tag__ :',
                            'value' => config('discordlogger.tag-role-id') ?: 'Inconnu',
                        ],
                    ],
                    'footer' => [
                        'text' => url()->current(),
                    ],
                    'author' => [
                        'name' => 'Utilisateur : ' . (\Auth::user() ? \Auth::user()->pseudo : 'Inconnu'),
                    ],
                ],
            ],
        ];

        return json_encode($payload);
    }
}
