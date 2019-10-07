/** @jsx jsx */

import useAxios from 'axios-hooks';
import { FunctionComponent } from 'react';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import Matrix from './matrix/Matrix';
import LevelStatistics from './model/LevelStatistics';
import Scores from './Scores';

export interface Props {
    level: number | undefined;
}

const Block = styled.div({
    textAlign: 'center',
    display: 'inline-block',
    verticalAlign: 'top',
    width: '38em'
});

const Layout = styled.div({
    width: '80em',
    margin: 'auto',
    textAlign: 'center'
});

const Message: FunctionComponent<{ message: string }> = ({ message }) => (
    <span
        css={{
            fontFamily: 'ibmconv',
            display: 'inline-block',
            marginTop: '15em'
        }}
    >
        {message}
    </span>
);

const App: FunctionComponent<Props> = ({ level }) => {
    if (level !== undefined && (level < 0 || level > 255)) {
        level = undefined;
    }

    const [{ data: levelStatistics, loading, error }] = useAxios<Array<LevelStatistics>>('/api/statistics');

    if (loading) {
        return (
            <Layout>
                <Message message="Loading..." />
            </Layout>
        );
    }

    if (error) {
        return (
            <Layout>
                <Message message="Network error" />
            </Layout>
        );
    }

    return (
        <div css={{ width: '80em', margin: 'auto' }}>
            <Block css={{ marginRight: '2em' }}>
                <Matrix css={{ margin: 'auto' }} statistics={levelStatistics} selectedLevel={level} />
            </Block>
            <Block css={{ marginLeft: '2em' }}>
                {level ? (
                    <Scores level={level} statistics={levelStatistics[level]} />
                ) : (
                    <Message message="Select a level" />
                )}
            </Block>
        </div>
    );
};

export default App;
