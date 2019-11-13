<?php declare(strict_types=1);

namespace App\Service;

use App\Exception\BadCodeException;
use App\Model\DecodedScore;

/**
 * Interface ScoreCodecInterface
 * @package App\Service
 */
interface ScoreCodecInterface
{
    /**
     * @param string $code
     * @return DecodedScore
     * @throws BadCodeException
     */
    public function decode(string $code): DecodedScore;

    /**
     * @param DecodedScore $score
     * @return string
     */
    public function encode(DecodedScore $score): string;
}