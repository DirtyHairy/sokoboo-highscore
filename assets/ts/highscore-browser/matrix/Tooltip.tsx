/** @jsx jsx */

import { FunctionComponent } from 'react';

import { jsx } from '@emotion/core';

import { formatSeconds } from '../model/Highscore';
import LevelStatistics from '../model/LevelStatistics';
import { WIDE } from './definitions';

export interface Props {
    level: number;
    statistics: LevelStatistics;
}

const TRANSFORM = 'translateY(-100%) translate(-5px, 5px)';

const Tooltip: FunctionComponent<Props> = props => (
    <div
        css={{
            position: 'absolute',
            width: '7em',
            backgroundColor: 'black',
            color: 'white',
            boxShadow: '0 0 32px 3px #333',
            borderCollapse: 'separate',
            padding: '0.5em',
            left: WIDE,
            zIndex: 10,
            transform: TRANSFORM,
            msTransform: TRANSFORM,
            pointerEvents: 'none',
            textAlign: 'center'
        }}
        className="tooltip"
    >
        <div css={{ marginBottom: '0.5em' }}>☰ {props.level}</div>
        <div css={{ marginBottom: '0.5em' }}>
            <i className="player-icon" /> {props.statistics.playedCount}
        </div>
        <div css={{ marginBottom: '0.5em' }}>
            # {props.statistics.bestScore ? props.statistics.bestScore.moves : '-'}
        </div>
        <div>
            <i className="clock-icon" />{' '}
            {props.statistics.bestScore ? formatSeconds(props.statistics.bestScore.seconds) : '-'}
        </div>
    </div>
);

export default Tooltip;
