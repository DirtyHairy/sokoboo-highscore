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
            $levelStatistics[$i] = (new LevelStatistics())
                ->setBestScore(random_int(10, 50))
                ->setPlayedCount(max(0, random_int($i * -5, 5 * (600 - $i))));
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
            $highScores[$i] = (new HighScore())
                ->setNick(sprintf("player%s", $i + 1))
                ->setScore(random_int(5, 100));
        }

        return $highScores;
    }
}
