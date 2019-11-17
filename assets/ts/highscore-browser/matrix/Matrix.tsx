/** @jsx jsx */

import { Fragment, FunctionComponent } from 'react';

import { jsx } from '@emotion/core';

import LevelStatistics from '../../model/LevelStatistics';
import Cell from './Cell';
import EmptyCell from './EmptyCell';

export interface Props {
    selectedLevel: number | undefined;
    statistics: Array<LevelStatistics>;

    className?: string;
}

const LEVEL_COUNT = 100;

const Matrix: FunctionComponent<Props> = ({ statistics, selectedLevel, className }) => {
    const maxPlayed = Math.max(...statistics.map(x => x.playedCount));

    return (
        <table css={{ borderCollapse: 'collapse', fontFamily: 'vga8' }} className={className}>
            <tbody>
                {new Array(Math.ceil(LEVEL_COUNT / 10)).fill(1).map((_, i) => (
                    <tr key={i}>
                        <Fragment>
                            {new Array(10).fill(1).map((_, j) => {
                                const level = i * 10 + j;

                                return level < LEVEL_COUNT ? (
                                    <Cell
                                        selected={level === selectedLevel}
                                        key={level}
                                        level={level}
                                        statistics={statistics[level]}
                                        maxPlayedCount={maxPlayed}
                                    />
                                ) : (
                                    <EmptyCell />
                                );
                            })}
                        </Fragment>
                    </tr>
                ))}
            </tbody>
        </table>
    );
};

export default Matrix;
