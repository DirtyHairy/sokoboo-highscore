/* @jsx jsx */

import useAxios from 'axios-hooks';
import { FunctionComponent } from 'react';

import { jsx } from '@emotion/core';

import { Container, Message } from './layout';
import Matrix from './matrix/Matrix';
import LevelStatistics from './model/LevelStatistics';

export interface Props {
    selectedLevel: number | undefined;
    className?: string;
}

const LevelMatrix: FunctionComponent<Props> = ({ selectedLevel, className }) => {
    const [{ data, loading, error }] = useAxios<Array<LevelStatistics>>('/api/statistics');

    if (error) {
        return (
            <Container className={className}>
                <Message>ERROR!</Message>
            </Container>
        );
    }

    if (loading) {
        return (
            <Container className={className}>
                <Message>Loading...</Message>
            </Container>
        );
    }

    return (
        <Container className={className}>
            <Matrix css={{ margin: 'auto' }} statistics={data} selectedLevel={selectedLevel} />
        </Container>
    );
};

export default LevelMatrix;
