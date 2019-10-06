/**@jsx jsx */

import { FunctionComponent } from 'react';

import { jsx } from '@emotion/core';

import LevelStatistics from '../model/LevelStatistics';
import { BORDER, WIDE } from './definitions';
import Tooltip from './Tooltip';

export interface Props {
    level: number;
    maxPlayedCount: number;
    statistics: LevelStatistics;
}

function color(x: number, max: number): string {
    const r = Math.round(((max - x) / max) * 255);
    const g = Math.round((x / max) * 255);

    return `rgb(${r},${g},0)`;
}

const Cell: FunctionComponent<Props> = props => (
    <td
        css={{
            height: WIDE,
            width: WIDE,
            border: BORDER,
            textAlign: 'center',
            color: 'rgba(50, 50, 50, 0.5)',
            position: 'relative',

            '&:hover': {
                color: 'black'
            },

            '& .tooltip': {
                display: 'none'
            },

            '&:hover .tooltip': {
                display: 'initial'
            }
        }}
        style={{ backgroundColor: color(props.statistics.playedCount, props.maxPlayedCount) }}
    >
        <a
            href={`/highscores/${props.level}`}
            css={{
                display: 'inline-block',
                width: '100%',
                height: '100%',
                color: 'inherit',
                lineHeight: WIDE,
                textDecoration: 'none'
            }}
        >
            {props.level.toString(16).padStart(2, '0')}
        </a>
        <Tooltip level={props.level} statistics={props.statistics} />
    </td>
);

export default Cell;
