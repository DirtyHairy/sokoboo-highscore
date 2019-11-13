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
     * @var HighScore|null
     */
    private $bestScore;

    /**
     * LevelStatistics constructor.
     *
     * @param int $playedCount
     * @param HighScore $bestScore
     */
    public function __construct(int $playedCount, HighScore $bestScore = null)
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
     * @return HighScore
     */
    public function getBestScore(): ?HighScore
    {
        return $this->bestScore;
    }

    /**
     * @param HighScore $bestScore
     * @return LevelStatistics
     */
    public
    function setBestScore(?HighScore $bestScore): LevelStatistics
    {
        $this->bestScore = $bestScore;
        return $this;
    }
}