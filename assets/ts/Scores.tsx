/** @jsx jsx */

import useAxios from 'axios-hooks';
import { Fragment, FunctionComponent } from 'react';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import Highscore from './model/Highscore';
import LevelStatistics from './model/LevelStatistics';

export interface Props {
    level: number;
    statistics: LevelStatistics;
}

function heading(level: number, players: number): string {
    return `Level: ${level}/${level.toString(16).padStart(2, '0')} , Players: ${players}`;
}

const WIDTH = heading(255, 1000000000).length;

function line(highscore: Highscore, padScore: number): string {
    const score = highscore.score.toString().padStart(padScore, ' ');

    return `${highscore.nick} ${'.'.repeat(Math.max(WIDTH - 2 - highscore.nick.length - score.length, 0))} ${score}`;
}

const Message: FunctionComponent<{ message: string }> = ({ message }) => (
    <span
        css={{
            fontFamily: 'ibmconv',
            display: 'inline-block',
            marginTop: '15em'
        }}
    >
        {message}
    </span>
);

const Line = styled.pre({
    textAlign: 'center',
    fontFamily: 'ibmconv',
    lineHeight: '2em',
    margin: 0,
    display: 'block'
});

const Scores: FunctionComponent<Props> = ({ level, statistics }) => {
    const [{ data: highscores, loading, error }] = useAxios<Array<Highscore>>(`/api/level/${level}/highscore`);

    if (loading) {
        return <Message message="Loading..." />;
    }

    if (error) {
        return <Message message="Network error" />;
    }

    highscores.sort((a, b) => a.score - b.score);
    const scoreSlice = highscores.slice(0, 15);
    const maxScoreLength = Math.max(...scoreSlice.map(s => s.score.toString().length));

    return (
        <Fragment>
            {[
                <Line css={{ marginBottom: '2em' }} key="heading">
                    {`${statistics.playedCount} Scores for Level ${level}/$${level.toString(16).padStart(2, '0')}`}
                </Line>,
                ...highscores.slice(0, 15).map((h, i) => <Line key={i}>{line(h, maxScoreLength)}</Line>)
            ]}
        </Fragment>
    );
};

export default Scores;