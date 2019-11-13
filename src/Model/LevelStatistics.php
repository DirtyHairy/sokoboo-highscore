<?php declare(strict_types=1);


namespace App\Model;

/**
 * Class LevelStatistics
 * @package App\Model
 */
class LevelStatistics
{
    /**
     * @var int
     */
    private $playedCount = 0;

    /**
     * @var Highscore|null
     */
    private $bestScore;

    /**
     * LevelStatistics constructor.
     *
     * @param int $playedCount
     * @param Highscore $bestScore
     */
    public function __construct(int $playedCount, Highscore $bestScore = null)
    {
        $this->playedCount = $playedCount;
        $this->bestScore = $bestScore;
    }

    /**
     * @return int
     */
    public function getPlayedCount(): int
    {
        return $this->playedCount;
    }

    /**
     * @param int $playedCount
     * @return LevelStatistics
     */
    public function setPlayedCount(int $playedCount): LevelStatistics
    {
        $this->playedCount = $playedCount;
        return $this;
    }

    /**
     * @return Highscore
     */
    public function getBestScore(): ?Highscore
    {
        return $this->bestScore;
    }

    /**
     * @param Highscore $bestScore
     * @return LevelStatistics
     */
    public
    function setBestScore(?Highscore $bestScore): LevelStatistics
    {
        $this->bestScore = $bestScore;
        return $this;
    }
}