/* @jsx jsx */

import useAxios from 'axios-hooks';
import { number } from 'prop-types';
import { FunctionComponent } from 'react';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import Matrix from './matrix/Matrix';
import LevelStatistics from './model/LevelStatistics';

export interface Props {
    selectedLevel: number | undefined;
}

const Container = styled.div({
    width: '38em',
    textAlign: 'center'
});

const Message = styled.span({
    fontFamily: 'ibmconv',
    display: 'inline-block',
    marginTop: '10em'
});

const LevelMatrix: FunctionComponent<Props> = ({ selectedLevel }) => {
    const [{ data, loading, error }] = useAxios<Array<LevelStatistics>>('/api/statistics');

    if (error) {
        return (
            <Container>
                <Message>ERROR!</Message>
            </Container>
        );
    }

    if (loading) {
        return (
            <Container>
                <Message>Loading...</Message>
            </Container>
        );
    }

    return (
        <Container>
            <Matrix css={{ margin: 'auto' }} statistics={data} selectedLevel={selectedLevel} />
        </Container>
    );
};

export default LevelMatrix;
