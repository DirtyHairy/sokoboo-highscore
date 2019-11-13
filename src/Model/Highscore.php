<?php declare(strict_types=1);


namespace App\Model;

use App\Model\Entity\ScoreEntry;

/**
 * Class Highscore
 * @package App\Model
 */
class Highscore
{
    /** @var string */
    private $nick = "";

    /** @var int */
    private $level = 0;

    /** @var int */
    private $moves = 0;

    /** @var int */
    private $seconds = 0;

    /** @var int */
    private $timestamp = 0;

    /** @var int */
    private $rank = 0;

    /**
     * @param ScoreEntry $scoreEntry
     * @return Highscore
     */
    public static function fromScoreEntry(ScoreEntry $scoreEntry): Highscore
    {
        return (new Highscore())
            ->setNick($scoreEntry->getNick())
            ->setMoves($scoreEntry->getMoves())
            ->setSeconds($scoreEntry->getSeconds())
            ->setTimestamp($scoreEntry->getTimestamp())
            ->setLevel($scoreEntry->getLevel());
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     * @return Highscore
     */
    public function setTimestamp(int $timestamp): Highscore
    {
        $this->timestamp = $timestamp;
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
     * @return Highscore
     */
    public function setSeconds(int $seconds): Highscore
    {
        $this->seconds = $seconds;
        return $this;
    }

    /**
     * @return string
     */
    public function getNick(): string
    {
        return $this->nick;
    }

    /**
     * @param string $nick
     * @return Highscore
     */
    public function setNick(string $nick): Highscore
    {
        $this->nick = $nick;
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
     * @return Highscore
     */
    public function setMoves(int $moves): Highscore
    {
        $this->moves = $moves;
        return $this;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     * @return Highscore
     */
    public function setRank(int $rank): Highscore
    {
        $this->rank = $rank;
        return $this;
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
     * @return Highscore
     */
    public function setLevel(int $level): Highscore
    {
        $this->level = $level;
        return $this;
    }
}