/** @jsx jsx */

import { Fragment, FunctionComponent } from 'react';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import LevelStatistics from '../../model/LevelStatistics';
import Cell from './Cell';
import { BORDER, NARROW, WIDE } from './definitions';
import EmptyCell from './EmptyCell';

export interface Props {
    selectedLevel: number | undefined;
    statistics: Array<LevelStatistics>;

    className?: string;
}

const CellN = styled.td({
    height: NARROW,
    width: WIDE,
    border: BORDER,
    textAlign: 'center'
});

const CellNW = styled.td({
    height: WIDE,
    width: NARROW,
    border: BORDER,
    textAlign: 'center'
});

const CellW = styled.td({
    height: NARROW,
    width: NARROW,
    border: BORDER,
    textAlign: 'center'
});

const LEVEL_COUNT = 100;

const Matrix: FunctionComponent<Props> = ({ statistics, selectedLevel, className }) => {
    const maxPlayed = Math.max(...statistics.map(x => x.playedCount));

    return (
        <table css={{ borderCollapse: 'collapse', fontFamily: 'vga8' }} className={className}>
            <thead>
                <tr>
                    <CellNW />
                    <Fragment>
                        {new Array(10).fill(1).map((_, i) => (
                            <CellN key={i}>{i.toString(16).toUpperCase()}</CellN>
                        ))}
                    </Fragment>
                </tr>
            </thead>
            <tbody>
                {new Array(Math.ceil(LEVEL_COUNT / 10)).fill(1).map((_, i) => (
                    <tr key={i}>
                        <CellW>{i.toString(10).toUpperCase()}</CellW>
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
