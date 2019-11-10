/** @jsx jsx */

import useAxios from 'axios-hooks';
import { Fragment, FunctionComponent } from 'react';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

import Highscore, { formatSeconds, formatTimestamp } from './model/Highscore';
import LevelStatistics from './model/LevelStatistics';

const Title = styled.div({ fontFamily: 'ibmconv', textAlign: 'center', marginBottom: '2em' });

export interface Props {
    level: number;
    scores: Array<Highscore>;
    className?: string;
}

const Scores: FunctionComponent<Props> = ({ level, scores, className }) => {
    return (
        <div className={className} css={{ zIndex: 10 }}>
            <Title>----====≡≡≡≡ LEVEL {level} ≡≡≡≡====----</Title>
            <table
                css={{
                    fontFamily: 'ibmconv',
                    textAlign: 'left'
                }}
            >
                <thead>
                    <tr>
                        <td css={{ width: '7em' }}>PLACE</td>
                        <td css={{ width: '7em' }}>MOVES</td>
                        <td css={{ width: '10em' }}>TIME</td>
                        <td css={{ width: '13em', textAlign: 'right' }}>DATE</td>
                        <td css={{ width: '21em', textAlign: 'right' }}>NAME</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </thead>
                <tbody css={{ lineHeight: '2em', whiteSpace: 'nowrap' }}>
                    {Array(Math.min(10, scores.length))
                        .fill(1)
                        .map((_, i) => {
                            const h = scores[i];

                            return (
                                <tr key={i}>
                                    <td>{i + 1}</td>
                                    <td>{h.moves}</td>
                                    <td>{formatSeconds(h.seconds)}</td>
                                    <td css={{ textAlign: 'right' }}>{formatTimestamp(h.timestamp)}</td>
                                    <td css={{ width: '13em', textAlign: 'right' }}>{h.nick}</td>
                                </tr>
                            );
                        })}
                </tbody>
            </table>
        </div>
    );
};

export default Scores;
