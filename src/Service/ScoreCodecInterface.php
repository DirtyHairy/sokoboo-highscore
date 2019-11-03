<?php

namespace App\Service;

use App\Exception\BadCodeException;
use App\Model\DecodedScore;

interface ScoreCodecInterface
{
    /**
     * @param string $code
     * @return DecodedScore
     * @throws BadCodeException
     */
    public function decode(string $code): DecodedScore;

    public function encode(DecodedScore $score): string;
}