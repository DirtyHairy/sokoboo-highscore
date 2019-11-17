/** @jsx jsx */

import useAxios from 'axios-hooks';
import { FunctionComponent } from 'react';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import Highscore, { formatSeconds, formatTimestamp } from '../model/Highscore';

const Container = styled.div({
    width: '58em',
    height: '28em',
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'flex-start',
    alignItems: 'center'
});

const Message = styled.div({ ftextAlign: 'center' });

const Title: FunctionComponent<{ level: number }> = ({ level }) => (
    <div css={{ textAlign: 'center', marginBottom: '2em' }}>
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
                    textAlign: 'left',
                    tableLayout: 'fixed',
                    width: '58em'
                }}
            >
                <thead>
                    <tr>
                        <td css={{ width: '7em' }}>PLACE</td>
                        <td css={{ width: '7em' }}>MOVES</td>
                        <td css={{ width: '9em' }}>TIME</td>
                        <td css={{ width: '11em', textAlign: 'right' }}>DATE</td>
                        <td css={{ width: '21em', textAlign: 'right' }}>NAME</td>
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
                                    <td>{i + 1}</td>
                                    <td>{h ? h.moves : '.'.repeat(6)}</td>
                                    <td>{h ? formatSeconds(h.seconds) : '.'.repeat(8)}</td>
                                    <td css={{ textAlign: 'right' }}>
                                        {h ? formatTimestamp(h.timestamp) : '.'.repeat(10)}
                                    </td>
                                    <td css={{ width: '21em', textAlign: 'right' }}>{h ? h.nick : '.'.repeat(20)}</td>
                                </tr>
                            );
                        })}
                </tbody>
            </table>
        </Container>
    );
};

export default Scores;
