/** @jsx jsx */

import { FunctionComponent } from 'react';

import { jsx } from '@emotion/core';

import { Container, Message } from './layout';

export interface Props {
    level: number | undefined;
    className?: string;
}

const Scores: FunctionComponent<Props> = ({ level, className }) => {
    return (
        <Container className={className}>
            <Message>Select a level</Message>
        </Container>
    );
};

export default Scores;
