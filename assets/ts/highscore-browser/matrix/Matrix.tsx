/** @jsx jsx */

import useAxios from 'axios-hooks';
import { Fragment, FunctionComponent } from 'react';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import LevelStatistics from '../../model/LevelStatistics';
import Cell from './Cell';
import { BORDER } from './definitions';
import EmptyCell from './EmptyCell';

export interface Props {
    className?: string;
}

const Container = styled.div({ height: '31em' });

const Message = styled.div({
    textAlign: 'center',
    marginTop: '10em'
});

const LEVEL_COUNT = 100;

const Matrix: FunctionComponent<Props> = ({ className }) => {
    const [{ data: statistics, loading: loading, error: error }] = useAxios<Array<LevelStatistics>>('/api/statistics');

    if (loading) {
        return (
            <Container>
                <Message>Loading...</Message>
            </Container>
        );
    }

    if (error) {
        console.log(error);

        return (
            <Container>
                <Message>Network error</Message>
            </Container>
        );
    }

    const maxPlayed = Math.max(...statistics.map(x => x.playedCount));

    return (
        <Container>
            <table css={{ borderSpacing: 0, border: BORDER }} className={className}>
                <tbody>
                    {new Array(Math.ceil(LEVEL_COUNT / 10)).fill(1).map((_, i) => (
                        <tr key={i}>
                            <Fragment>
                                {new Array(10).fill(1).map((_, j) => {
                                    const level = i * 10 + j;

                                    return level < LEVEL_COUNT ? (
                                        <Cell
                                            selected={false}
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
        </Container>
    );
};

export default Matrix;
