<?php


namespace App\Model;

/**
 * Class DecodedScore
 * @package App\Model
 */
class DecodedScore
{

    /** @var int */
    private $level = 0;


    /** @var int */
    private $moves = 0;

    /** @var int */
    private $seconds = 0;

    /**
     * DecodedScore constructor.
     * @param int $level
     * @param int $moves
     * @param int $seconds
     */
    public function __construct(int $level, int $moves, int $seconds)
    {
        $this->level = $level;
        $this->moves = $moves;
        $this->seconds = $seconds;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     * @return DecodedScore
     */
    public function setLevel(int $level): DecodedScore
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return int
     */
    public function getMoves(): int
    {
        return $this->moves;
    }

    /**
     * @param int $moves
     * @return DecodedScore
     */
    public function setMoves(int $moves): DecodedScore
    {
        $this->moves = $moves;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeconds(): int
    {
        return $this->seconds;
    }

    /**
     * @param int $seconds
     * @return DecodedScore
     */
    public function setSeconds(int $seconds): DecodedScore
    {
        $this->seconds = $seconds;
        return $this;
    }
}