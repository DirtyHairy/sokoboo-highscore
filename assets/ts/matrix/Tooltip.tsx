/** @jsx jsx */

import { FunctionComponent } from 'react';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import LevelStatistics from '../model/LevelStatistics';
import { WIDE } from './definitions';

export interface Props {
    level: number;
    statistics: LevelStatistics;
}

const PlayerIcon = styled.i({
    width: '1rem',
    height: '1rem',
    backgroundImage: 'url("images/player.png")',
    backgroundSize: 'contain',
    display: 'inline-block',
    verticalAlign: 'bottom'
});

const TRANSFORM = 'translateY(-100%) translate(-5px, 5px)';

const Tooltip: FunctionComponent<Props> = props => (
    <div
        css={{
            position: 'absolute',
            width: '7rem',
            backgroundColor: 'black',
            color: 'white',
            boxShadow: '0 0 32px 3px #333',
            borderCollapse: 'separate',
            padding: '0.5rem',
            left: WIDE,
            zIndex: 10,
            transform: TRANSFORM,
            msTransform: TRANSFORM,
            pointerEvents: 'none',
            textAlign: 'center'
        }}
        className="tooltip"
    >
        <div css={{ marginBottom: '0.5rem' }}>
            ☰ {props.level}/${props.level.toString(16).padStart(2, '0')}
        </div>
        <div css={{ marginBottom: '0.5rem' }}>
            <PlayerIcon /> {props.statistics.playedCount}
        </div>
        <div># {props.statistics.bestScore}</div>
    </div>
);

export default Tooltip;
