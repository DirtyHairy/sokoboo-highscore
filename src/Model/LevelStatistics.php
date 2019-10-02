<?php


namespace App\Model;


class LevelStatistics
{
    /**
     * @var int
     */
    private $playedCount = 0;

    /**
     * @var int
     */
    private $bestScore = 0;

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
     * @return int
     */
    public function getBestScore(): int
    {
        return $this->bestScore;
    }

    /**
     * @param int $bestScore
     * @return LevelStatistics
     */
    public function setBestScore(int $bestScore): LevelStatistics
    {
        $this->bestScore = $bestScore;
        return $this;
    }
}