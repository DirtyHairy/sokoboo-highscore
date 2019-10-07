/** @jsx jsx */

import { FunctionComponent } from 'react';

import { jsx } from '@emotion/core';

import LevelMatrix from './LevelMatrix';
import Scores from './Scores';

export interface Props {
    level: number | undefined;
}

const App: FunctionComponent<Props> = ({ level }) => (
    <div css={{ width: '80em', margin: 'auto' }}>
        <LevelMatrix css={{ width: '38em', marginRight: '2em' }} selectedLevel={level} />
        <Scores css={{ width: '38em', marginRight: '2em' }} level={level} />
    </div>
);

export default App;
