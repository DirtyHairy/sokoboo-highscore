<?php


namespace App\Model;


class HighScore
{
    /** @var string */
    private $nick = "";

    /** @var int */
    private $moves = 0;

    /** @var int */
    private $seconds = 0;

    /**
     * HighScore constructor.
     *
     * @param string $nick
     * @param int $moves
     * @param int $seconds
     */
    public function __construct(string $nick, int $moves, int $seconds)
    {
        $this->nick = $nick;
        $this->moves = $moves;
        $this->seconds = $seconds;
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
     * @return HighScore
     */
    public function setSeconds(int $seconds): HighScore
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
    public function getMoves(): int
    {
        return $this->moves;
    }

    /**
     * @param int $moves
     * @return HighScore
     */
    public function setMoves(int $moves): HighScore
    {
        $this->moves = $moves;
        return $this;
    }
}