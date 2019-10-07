/** @jsx jsx */

import { Fragment, FunctionComponent } from 'react';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import LevelStatistics from '../model/LevelStatistics';
import Cell from './Cell';
import { BORDER, NARROW, WIDE } from './definitions';

export interface Props {
    selectedLevel: number | undefined;
    statistics: Array<LevelStatistics>;
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

const Matrix: FunctionComponent<Props> = ({ statistics, selectedLevel }) => {
    const maxPlayed = Math.max(...statistics.map(x => x.playedCount));

    return (
        <Fragment>
            <table css={{ borderCollapse: 'collapse' }}>
                <thead>
                    <tr>
                        <CellNW />
                        <Fragment>
                            {new Array(16).fill(1).map((_, i) => (
                                <CellN key={i}>{i.toString(16).toUpperCase()}</CellN>
                            ))}
                        </Fragment>
                    </tr>
                </thead>
                <tbody>
                    {new Array(16).fill(1).map((_, i) => (
                        <tr key={i}>
                            <CellW>{i.toString(16).toUpperCase()}</CellW>
                            <Fragment>
                                {new Array(16).fill(1).map((_, j) => {
                                    const level = i * 16 + j;

                                    return (
                                        <Cell
                                            selected={level === selectedLevel}
                                            key={level}
                                            level={level}
                                            statistics={statistics[level]}
                                            maxPlayedCount={maxPlayed}
                                        />
                                    );
                                })}
                            </Fragment>
                        </tr>
                    ))}
                </tbody>
            </table>
        </Fragment>
    );
};

export default Matrix;
