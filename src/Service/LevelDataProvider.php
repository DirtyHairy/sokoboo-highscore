<?php


namespace App\Service;


use App\Model\HighScore;
use App\Model\LevelStatistics;

class LevelDataProvider
{
    /**
     * @return LevelStatistics[]
     */
    public function levelStatistics(): array
    {
        /** @var LevelStatistics[] $levelStatistics */
        $levelStatistics = [];

        for ($i = 0; $i <= 255; $i++) {
            $highScore = new HighScore(
                "someplayer",
                random_int(10, 50),
                random_int(30, 5400),
                random_int(0, 50 * 24 * 3600 * 365)
            );

            $levelStatistics[$i] = new LevelStatistics(max(0, random_int($i * -5, 5 * (600 - $i))), $highScore);
        }

        return $levelStatistics;
    }

    /**
     * @param int $level
     * @return HighScore[]
     */
    public function highScoresForLevel(int $level): array
    {
        if ($level < 0 || $level > 255) {
            return [];
        }

        /** @var HighScore[] $highScores */
        $highScores = [];

        for ($i = 0; $i < 50; $i++) {
            $highScores[$i] = new HighScore(
                sprintf("player%s", $i + 1),
                random_int(5, 100),
                random_int(30, 5400),
                random_int(0, 50 * 24 * 3600 * 365)
            );
        }

        return $highScores;
    }
}
