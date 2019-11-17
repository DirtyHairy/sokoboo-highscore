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

const ChangeLevel = styled.a({
    fontFamily: 'ibmconv',
    color: 'white',
    textDecoration: 'none',
    '&:visited': { color: 'white', textDecoration: 'none' }
});

const Message = styled.div({ textAlign: 'center' });

const Title: FunctionComponent<{ level: number; onNextLevel?: () => void; onPreviousLevel?: () => void }> = ({
    level,
    onNextLevel,
    onPreviousLevel
}) => (
    <div css={{ textAlign: 'center', marginBottom: '1em', lineHeight: '2em' }}>
        Leader Board <br />
        <ChangeLevel
            href={onPreviousLevel ? '#' : undefined}
            onClick={onPreviousLevel ? e => (e.preventDefault(), onPreviousLevel()) : undefined}
        >
            ----====≡≡≡≡
        </ChangeLevel>{' '}
        LEVEL <div css={{ display: 'inline-block', width: '2em', textAlign: 'right' }}>{level}</div>{' '}
        <ChangeLevel
            href={onNextLevel ? '#' : undefined}
            onClick={onNextLevel ? e => (e.preventDefault(), onNextLevel()) : undefined}
            css={{ fontFamily: 'ibmconv' }}
        >
            ≡≡≡≡====----
        </ChangeLevel>
    </div>
);

export interface Props {
    level: number;
    highlightRank?: number;
    className?: string;
    onPreviousLevel?: () => void;
    onNextLevel?: () => void;
}

const Scores: FunctionComponent<Props> = props => {
    const [{ data: scores, loading: scoresLoading, error: loadError }] = useAxios<Array<Highscore>>(
        `/api/level/${props.level === undefined ? 0 : props.level}/highscore`
    );

    if (scoresLoading || loadError) {
        return (
            <Container className={props.className}>
                <Title level={props.level} onPreviousLevel={props.onPreviousLevel} onNextLevel={props.onNextLevel} />
                <Message>{scoresLoading ? 'Loading...' : 'Network error'}</Message>
            </Container>
        );
    }

    return (
        <Container className={props.className}>
            <Title level={props.level} onPreviousLevel={props.onPreviousLevel} onNextLevel={props.onNextLevel} />
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
                                <tr key={i} css={props.highlightRank === i + 1 ? { color: 'red' } : undefined}>
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
