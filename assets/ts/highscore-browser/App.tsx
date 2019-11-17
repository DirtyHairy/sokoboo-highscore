/** @jsx jsx */

import useAxios from 'axios-hooks';
import { Fragment, FunctionComponent, useEffect, useMemo, useRef } from 'react';
import { useHistory } from 'react-router';

import { jsx } from '@emotion/core';

import Scores from '../component/Scores';
import LevelStatistics from '../model/LevelStatistics';
import Matrix from './matrix/Matrix';

export interface Props {
    level: number | undefined;
}

const LEVEL_COUNT = 100;

const Message: FunctionComponent<{ message: string }> = ({ message }) => (
    <div
        css={{
            display: 'block',
            marginTop: '6em',
            textAlign: 'center'
        }}
    >
        {message}
    </div>
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

            if (levelRef.current === undefined) {
                return;
            }

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

    const pageLinkHandler = useMemo(() => {
        const handler = (e: MouseEvent) => {
            e.preventDefault();

            history.push('/highscores');
        };

        const a: HTMLAnchorElement | null = document.querySelector('a.navigation-highscores');

        if (a) {
            a.addEventListener('click', handler);
        }

        return handler;
    }, []);

    useEffect(
        () => () => {
            const a: HTMLAnchorElement | null = document.querySelector('a.navigation-highscores');

            if (a) {
                a.removeEventListener('click', pageLinkHandler);
            }
        },
        []
    );

    levelRef.current = level;

    if (level !== undefined && (level < 0 || level >= LEVEL_COUNT)) {
        level = undefined;
    }

    const [{ data: levelStatistics, loading: statisticsLoading, error: statisticsError }] = useAxios<
        Array<LevelStatistics>
    >('/api/statistics');

    if (statisticsLoading) {
        return <Message message="Loading..." />;
    }

    if (statisticsError) {
        return <Message message="Network error" />;
    }

    if (level !== undefined) {
        return (
            <Fragment>
                <Scores css={{ margin: 'auto' }} level={level} />
                <Matrix
                    css={{ marginLeft: 'auto', marginRight: 'auto', marginTop: '4em' }}
                    statistics={levelStatistics}
                    selectedLevel={level}
                />
            </Fragment>
        );
    } else {
        return (
            <Matrix
                css={{ margin: 'auto', transform: 'scale(1.5)', transformOrigin: 'top center' }}
                statistics={levelStatistics}
                selectedLevel={level}
            />
        );
    }
};

export default App;
