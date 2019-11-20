/** @jsx jsx */

import useAxios from 'axios-hooks';
import { FunctionComponent } from 'react';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import Highscore, { formatSeconds } from '../model/Highscore';
import { NO_HOVER } from '../util/sniffing';

export interface Props {
    level: number;
    highlightRank?: number;
    className?: string;
    onPreviousLevel?: () => void;
    onNextLevel?: () => void;
}

const Container = styled.div({ height: '31em' });

const NavLink: FunctionComponent<{ handler?: () => void }> = ({ handler, children }) => (
    <a
        css={{
            fontFamily: 'ibmconv',
            color: 'white',
            textDecoration: 'none',
            '&:visited': { color: 'white', textDecoration: 'none' },
            ...(handler && !NO_HOVER ? { '&:hover': { color: 'red' } } : {})
        }}
        href={handler ? '#' : undefined}
        onClick={handler ? e => (e.preventDefault(), handler()) : undefined}
    >
        {children}
    </a>
);

const Title: FunctionComponent<{ level: number; onNextLevel?: () => void; onPreviousLevel?: () => void }> = props => (
    <div css={{ textAlign: 'center', marginBottom: '1em', lineHeight: '2em' }}>
        Leader Board <br />
        <NavLink handler={props.onPreviousLevel}>----====≡≡≡≡</NavLink> LEVEL{' '}
        <div css={{ display: 'inline-block', width: '2em', textAlign: 'right' }}>{props.level}</div>{' '}
        <NavLink handler={props.onNextLevel}>≡≡≡≡====----</NavLink>
    </div>
);

const Scores: FunctionComponent<Props> = props => {
    const [{ data: scores, loading, error }] = useAxios<Array<Highscore>>(
        `/api/level/${props.level === undefined ? 0 : props.level}/highscore`
    );

    const title = <Title level={props.level} onPreviousLevel={props.onPreviousLevel} onNextLevel={props.onNextLevel} />;

    if (loading || error) {
        return (
            <Container className={props.className}>
                {title}
                <div css={{ textAlign: 'center', marginTop: '2em' }}>{loading ? 'Loading...' : 'Network error'}</div>
            </Container>
        );
    }

    return (
        <Container className={props.className}>
            {title}
            <table
                css={{
                    textAlign: 'center',
                    tableLayout: 'fixed',
                    margin: 'auto'
                }}
            >
                <thead>
                    <tr css={{ color: 'red' }}>
                        <td css={{ width: '2em', padding: '0 1em' }}></td>
                        <td css={{ width: '21em', padding: '0 1em' }}>Name</td>
                        <td css={{ width: '7em', paddingLeft: '0 1em' }}>Moves</td>
                        <td css={{ width: '9em', padding: '0 1em' }}>Time</td>
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
                                    <td css={{ textAlign: 'right', color: 'red' }}>{i + 1}</td>
                                    <td>{h ? h.nick : '---'}</td>
                                    <td>{h ? h.moves : '---'}</td>
                                    <td>{h ? formatSeconds(h.seconds) : '---'}</td>
                                </tr>
                            );
                        })}
                </tbody>
            </table>
        </Container>
    );
};

export default Scores;
