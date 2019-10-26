/** @jsx jsx */

import useAxios from 'axios-hooks';
import { Fragment, FunctionComponent, useEffect, useMemo, useRef } from 'react';
import { useHistory } from 'react-router';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import Matrix from './matrix/Matrix';
import LevelStatistics from './model/LevelStatistics';
import Scores from './Scores';

export interface Props {
    level: number | undefined;
}

const Layout = styled.div({
    display: 'flex',
    justifyContent: 'space-around',
    flexWrap: 'wrap',
    '&': {
        justifyContent: 'space-evenly'
    },
    marginLeft: '1rem',
    marginRight: '1rem'
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
            return level === undefined ? 0 : (level + 255) % 256;

        case 'ArrowRight':
            return level === undefined ? 0 : (level + 1) % 256;

        case 'ArrowUp':
            return level === undefined ? 0 : (level + 240) % 256;

        case 'ArrowDown':
            return level === undefined ? 0 : (level + 16) % 256;

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

    if (level !== undefined && (level < 0 || level > 255)) {
        level = undefined;
    }

    const [{ data: levelStatistics, loading, error }] = useAxios<Array<LevelStatistics>>('/api/statistics');

    if (loading) {
        return (
            <Layout>
                <Message message="Loading..." />
            </Layout>
        );
    }

    if (error) {
        return (
            <Layout>
                <Message message="Network error" />
            </Layout>
        );
    }

    if (level !== undefined) {
        return (
            <Layout>
                <Scores css={{ marginRight: '1rem' }} level={level} statistics={levelStatistics[level]} />
                <Matrix
                    css={{ marginTop: '3em', marginLeft: '1rem' }}
                    statistics={levelStatistics}
                    selectedLevel={level}
                />
            </Layout>
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
