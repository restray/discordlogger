<?php

namespace Restray\DiscordLogger;

class DiscordEmbedColor
{
    const INFO = "22015";
    const NOTICE = "65535";
    const WARNING = "11206400";
    const ERROR = "16733440";
    const CRITICAL = "16738816";
    const ALERT = "4201216";
    const EMERGENCY = "16711680";

    public static function get(string $level_name)
    {
        return constant(__NAMESPACE__ . '\DiscordEmbedColor::' . $level_name);
    }
}