/** @jsx jsx */

import { FunctionComponent, useEffect, useMemo, useRef } from 'react';
import { useHistory } from 'react-router';

import { jsx } from '@emotion/core';

import Scores from '../component/Scores';

export interface Props {
    level: number;
}

const LEVEL_COUNT = 100;

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

const Level: FunctionComponent<Props> = ({ level }) => {
    if (level < 0 || level >= LEVEL_COUNT) {
        level = 0;
    }

    const levelRef = useRef<number>(level);
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

    levelRef.current = level;

    return (
        <Scores
            css={{ margin: 'auto', marginBottom: '3em' }}
            level={level}
            onPreviousLevel={() => history.push(`/highscores/${(level! + LEVEL_COUNT - 1) % LEVEL_COUNT}`)}
            onNextLevel={() => history.push(`/highscores/${(level! + LEVEL_COUNT + 1) % LEVEL_COUNT}`)}
        />
    );
};

export default Level;
