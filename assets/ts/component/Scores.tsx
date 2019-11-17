/** @jsx jsx */

import useAxios from 'axios-hooks';
import { FunctionComponent } from 'react';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import Highscore, { formatSeconds } from '../model/Highscore';

const Container = styled.div({
    height: '30em',
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'flex-start',
    alignItems: 'center'
});

const Message = styled.div({ textAlign: 'center' });

const Title: FunctionComponent<{ level: number }> = ({ level }) => (
    <div css={{ textAlign: 'center', marginBottom: '1em', lineHeight: '2em' }}>
        Leader Board <br />
        <span css={{ fontFamily: 'ibmconv' }}>----====≡≡≡≡</span> LEVEL{' '}
        <div css={{ display: 'inline-block', width: '2em', textAlign: 'right' }}>{level}</div>{' '}
        <span css={{ fontFamily: 'ibmconv' }}>≡≡≡≡====----</span>
    </div>
);

export interface Props {
    level: number;
    highlightRank?: number;
    className?: string;
}

const Scores: FunctionComponent<Props> = ({ level, highlightRank: highlightScore, className }) => {
    const [{ data: scores, loading: scoresLoading, error: loadError }] = useAxios<Array<Highscore>>(
        `/api/level/${level === undefined ? 0 : level}/highscore`
    );

    if (scoresLoading || loadError) {
        return (
            <Container className={className}>
                <Title level={level} />
                <Message>{scoresLoading ? 'Loading...' : 'Network error'}</Message>
            </Container>
        );
    }

    return (
        <Container className={className}>
            <Title level={level} />
            <table
                css={{
                    textAlign: 'center',
                    tableLayout: 'fixed'
                }}
            >
                <thead>
                    <tr css={{ color: 'red' }}>
                        <td css={{ width: '2em', paddingRight: '1em' }}></td>
                        <td css={{ width: '21em', paddingLeft: '1em', paddingRight: '1em' }}>Name</td>
                        <td css={{ width: '7em', paddingLeft: '1em', paddingRight: '1em' }}>Moves</td>
                        <td css={{ width: '9em', paddingLeft: '1em' }}>Time</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </thead>
                <tbody css={{ lineHeight: '2em', whiteSpace: 'nowrap' }}>
                    {Array(Math.min(10))
                        .fill(1)
                        .map((_, i) => {
                            const h = scores[i];

                            return (
                                <tr key={i} css={highlightScore === i + 1 ? { color: 'red' } : undefined}>
                                    <td css={{ textAlign: 'right', color: 'red', width: '3em' }}>{i + 1}</td>
                                    <td css={{ width: '21em' }}>{h ? h.nick : '.'.repeat(20)}</td>
                                    <td>{h ? h.moves : '.'.repeat(7)}</td>
                                    <td>{h ? formatSeconds(h.seconds) : '.'.repeat(9)}</td>
                                </tr>
                            );
                        })}
                </tbody>
            </table>
        </Container>
    );
};

export default Scores;
