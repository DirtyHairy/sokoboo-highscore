/** @jsx jsx */

import { FunctionComponent, useEffect, useMemo } from 'react';
import { useHistory } from 'react-router';

import { jsx } from '@emotion/core';

import { applyStripes } from '../stripes/stripes';
import Level from './Level';
import Matrix from './matrix/Matrix';

export interface Props {
    level: number | undefined;
}

const App: FunctionComponent<Props> = ({ level }) => {
    const history = useHistory();

    useEffect(applyStripes, [level]);

    const linkOverrides = useMemo(() => {
        const handler = (e: MouseEvent) => {
            e.preventDefault();

            history.push('/highscores');
        };

        const anchors: HTMLAnchorElement[] = Array.from(document.querySelectorAll('a')).filter(a =>
            /\/highscores\/?$/.test(a.href)
        );

        anchors.forEach(a => a.addEventListener('click', handler));

        return { anchors, handler };
    }, []);

    useEffect(
        () => () => {
            const { anchors, handler } = linkOverrides;

            anchors.forEach(a => a.removeEventListener('click', handler));
        },
        []
    );

    return level === undefined ? <Matrix css={{ margin: 'auto', marginBottom: '5em' }} /> : <Level level={level} />;
};

export default App;
