<?php


namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class FormatExtension
 * @package App\Twig
 */
class FormatExtension extends AbstractExtension
{
    /**
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter("level_time", "App\Twig\FormatExtension::formatLevelTime")
        ];
    }

    /**
     * @param int $seconds
     * @return string
     */
    public static function formatLevelTime(int $seconds): string
    {
        /** @var string $hours */
        $hours = str_pad(strval(intdiv($seconds, 3600)), 2, "0", STR_PAD_LEFT);
        $seconds %= 3600;

        /** @var int $minutes */
        $minutes = str_pad(strval(intdiv($seconds, 60)), 2, "0", STR_PAD_LEFT);
        $seconds %= 60;

        $remainingSeconds = str_pad(strval($seconds), 2, "0", STR_PAD_LEFT);

        return $hours == "00" ? ($minutes . ":" . $remainingSeconds) : ($hours . ":" . $minutes . ":" . $remainingSeconds);
    }
}