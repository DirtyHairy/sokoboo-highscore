/** @jsx jsx */

import useAxios from 'axios-hooks';
import { FunctionComponent, useEffect, useMemo, useRef } from 'react';
import { useHistory } from 'react-router';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import Matrix from './matrix/Matrix';
import LevelStatistics from './model/LevelStatistics';
import Scores from './Scores';

export interface Props {
    level: number | undefined;
}

const Block = styled.div({
    textAlign: 'center',
    display: 'inline-block',
    verticalAlign: 'top',
    width: '38em'
});

const Layout = styled.div({
    width: '80em',
    margin: 'auto',
    textAlign: 'center'
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

function navigateLevel(level: number | undefined, e: KeyboardEvent): number {
    if (level === undefined) {
        return 0;
    }

    switch (e.key) {
        case 'ArrowLeft':
            return (level + 255) % 256;

        case 'ArrowRight':
            return (level + 1) % 256;

        case 'ArrowUp':
            return (level + 240) % 256;

        case 'ArrowDown':
            return (level + 16) % 256;

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

    return (
        <div css={{ width: '80em', margin: 'auto' }}>
            <Block css={{ marginRight: '2em' }}>
                <Matrix css={{ margin: 'auto' }} statistics={levelStatistics} selectedLevel={level} />
            </Block>
            <Block css={{ marginLeft: '2em' }}>
                {level !== undefined ? (
                    <Scores level={level} statistics={levelStatistics[level]} />
                ) : (
                    <Message message="Select a level" />
                )}
            </Block>
        </div>
    );
};

export default App;
