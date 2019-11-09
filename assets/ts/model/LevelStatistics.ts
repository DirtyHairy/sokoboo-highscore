import Highscore from './Highscore';

interface LevelStatistics {
    playedCount: number;
    bestScore: Highscore | null;
}

export default LevelStatistics;
