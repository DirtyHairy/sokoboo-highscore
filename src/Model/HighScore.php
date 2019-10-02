<?php


namespace App\Model;


class HighScore
{
    /** @var string */
    private $nick = "";

    /** @var int */
    private $score = 0;

    /**
     * @return string
     */
    public function getNick(): string
    {
        return $this->nick;
    }

    /**
     * @param string $nick
     * @return HighScore
     */
    public function setNick(string $nick): HighScore
    {
        $this->nick = $nick;
        return $this;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     * @return HighScore
     */
    public function setScore(int $score): HighScore
    {
        $this->score = $score;
        return $this;
    }
}