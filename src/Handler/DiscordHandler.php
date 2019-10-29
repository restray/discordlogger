<?php

namespace Restray\DiscordLogger\Handler;

use Restray\DiscordLogger\DiscordInterface;
use Monolog\Handler\AbstractProcessingHandler;

class DiscordHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        $discord = new DiscordInterface;

        $discord->send($record['level_name'],
                       $record['message'],
                       $record['context'],
                       $record['datetime']->format('Y-m-d\TH:i:s.u'));
    }
}
