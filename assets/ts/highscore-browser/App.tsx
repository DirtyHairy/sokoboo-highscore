/** @jsx jsx */

import useAxios from 'axios-hooks';
import { FunctionComponent, useEffect, useMemo, useRef } from 'react';
import { useHistory } from 'react-router';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import Matrix from './matrix/Matrix';
import Highscore from './model/Highscore';
import LevelStatistics from './model/LevelStatistics';
import Scores from './Scores';

export interface Props {
    level: number | undefined;
}

const LEVEL_COUNT = 100;

const Layout = styled.div({
    display: 'flex',
    justifyContent: 'space-around',
    flexWrap: 'wrap',
    '&': {
        justifyContent: 'space-evenly'
    }
});

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

function navigateLevel(level: number | undefined, e: KeyboardEvent): number | undefined {
    if (e.ctrlKey || e.shiftKey) {
        return level;
    }

    switch (e.key) {
        case 'ArrowLeft':
            return level === undefined ? 0 : (level + LEVEL_COUNT - 1) % LEVEL_COUNT;

        case 'ArrowRight':
            return level === undefined ? 0 : (level + 1) % LEVEL_COUNT;

        case 'ArrowUp':
            return level === undefined ? 0 : (level + LEVEL_COUNT - 10) % LEVEL_COUNT;

        case 'ArrowDown':
            return level === undefined ? 0 : (level + 10) % LEVEL_COUNT;

        default:
            return level;
    }
}

const App: FunctionComponent<Props> = ({ level }) => {
    const levelRef = useRef<number | undefined>(level);
    const history = useHistory();

    const keydownHandler = useMemo(() => {
        const handler = (e: KeyboardEvent) => {
            const newLevel = navigateLevel(levelRef.current, e);

            if (newLevel === levelRef.current) {
                return;
            }

            e.preventDefault();

            history.push(`/highscores/${newLevel}`);
        };

        window.addEventListener('keydown', handler);

        return handler;
    }, []);

    useEffect(() => () => window.removeEventListener('keydown', keydownHandler), []);

    levelRef.current = level;

    if (level !== undefined && (level < 0 || level >= LEVEL_COUNT)) {
        level = undefined;
    }

    const [{ data: levelStatistics, loading: statisticsLoading, error: statisticsError }] = useAxios<
        Array<LevelStatistics>
    >('/api/statistics');

    const [{ data: scores, loading: highscoresLoading, error: highscoresError }] = useAxios<Array<Highscore>>(
        `/api/level/${level === undefined ? 0 : level}/highscore`
    );

    if (statisticsLoading || highscoresLoading) {
        return (
            <Layout>
                <Message message="Loading..." />
            </Layout>
        );
    }

    if (statisticsError || highscoresError) {
        return (
            <Layout>
                <Message message="Network error" />
            </Layout>
        );
    }

    if (level !== undefined) {
        return (
            <div
                css={{
                    display: 'flex',
                    justifyContent: 'space-around',
                    flexWrap: 'wrap',
                    '&': {
                        justifyContent: 'space-evenly'
                    }
                }}
            >
                <Scores css={{ marginRight: '1rem', marginLeft: '1rem' }} level={level} scores={scores} />
                <Matrix
                    css={{ marginLeft: '1rem', marginRight: '1rem', marginTop: '3em' }}
                    statistics={levelStatistics}
                    selectedLevel={level}
                />
            </div>
        );
    } else {
        return (
            <Layout>
                <Matrix css={{ margin: 'auto' }} statistics={levelStatistics} selectedLevel={level} />
            </Layout>
        );
    }
};

export default App;
