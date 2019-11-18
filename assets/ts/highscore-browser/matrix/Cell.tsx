/**@jsx jsx */

import { FunctionComponent } from 'react';
import { Link } from 'react-router-dom';

import { jsx } from '@emotion/core';

import LevelStatistics from '../../model/LevelStatistics';
import { IS_FIREFOX, NO_HOVER } from '../../util/sniffing';
import { BORDER, WIDE } from './definitions';
import Tooltip from './Tooltip';

export interface Props {
    level: number;
    maxPlayedCount: number;
    statistics: LevelStatistics;
    selected: boolean;
}

function color(x: number, max: number): string {
    if (max === 0) {
        return 'rgb(255,0,0)';
    }

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
            color: props.selected ? 'white' : 'rgba(50, 50, 50, 0.5)',
            position: 'relative',
            backgroundClip: IS_FIREFOX ? 'padding-box' : 'border-box',
            padding: 0,

            '& .tooltip': {
                display: 'none'
            },

            ...(NO_HOVER
                ? {}
                : {
                      '&:hover': {
                          color: props.selected ? 'white' : 'black'
                      },

                      '&:hover .tooltip': {
                          display: 'initial'
                      }
                  })
        }}
        style={
            props.selected
                ? {
                      backgroundImage: `linear-gradient(45deg, ${color(
                          props.statistics.playedCount,
                          props.maxPlayedCount
                      )}, black)`
                  }
                : { backgroundColor: color(props.statistics.playedCount, props.maxPlayedCount) }
        }
    >
        <Link
            to={`/highscores/${props.level}`}
            css={{
                display: 'inline-block',
                width: '100%',
                height: '100%',
                color: 'inherit',
                lineHeight: WIDE,
                textDecoration: 'none'
            }}
        >
            {props.level}
        </Link>
        {NO_HOVER || <Tooltip level={props.level} statistics={props.statistics} />}
    </td>
);

export default Cell;
