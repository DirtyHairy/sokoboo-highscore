/* @jsx jsx */

import useAxios from 'axios-hooks';
import { Fragment, FunctionComponent, MouseEvent } from 'react';

import { jsx } from '@emotion/core';
import styled from '@emotion/styled';

const WIDE = '2em';
const NARROW = '1em';

const CellN = styled.td({
    height: NARROW,
    width: WIDE,
    border: '1px solid white',
    textAlign: 'center'
});

const CellNW = styled.td({
    height: NARROW,
    width: NARROW,
    border: '1px solid white',
    textAlign: 'center'
});

const CellW = styled.td({
    height: WIDE,
    width: NARROW,
    border: '1px solid white',
    textAlign: 'center'
});

const Cell = styled.td({
    height: WIDE,
    width: WIDE,
    border: '1px solid white',
    textAlign: 'center',
    color: 'rgba(50, 50, 50, 0.5)',
    '&:hover': {
        color: 'black'
    }
});

interface LevelStatistics {
    playedCount: number;
    bestScore: number;
}

function color(x: number, max: number): string {
    const r = Math.round(((max - x) / max) * 255);
    const g = Math.round((x / max) * 255);

    return `rgb(${r},${g},0)`;
}

const HighscoreOverview: FunctionComponent = () => {
    const [{ data, loading, error }] = useAxios<Array<LevelStatistics>>('/api/statistics');

    if (error) {
        return <div>ERROR!</div>;
    }

    if (loading) {
        return <div>Loading...</div>;
    }

    const maxPlayed = Math.max(...data.map(x => x.playedCount));

    return (
        <table css={{ borderCollapse: 'collapse' }}>
            <thead>
                <tr>
                    <CellNW />
                    <Fragment>
                        {new Array(16).fill(1).map((_, i) => (
                            <CellN key={i}>{i.toString(16).toUpperCase()}</CellN>
                        ))}
                    </Fragment>
                </tr>
            </thead>
            <tbody>
                {new Array(16).fill(1).map((_, i) => (
                    <tr key={i}>
                        <CellW>{i.toString(16).toUpperCase()}</CellW>
                        <Fragment>
                            {new Array(16).fill(1).map((_, j) => (
                                <Cell
                                    key={j}
                                    style={{ backgroundColor: color(data[i * 16 + j].playedCount, maxPlayed) }}
                                >
                                    <a
                                        href="#"
                                        css={{
                                            display: 'inline-block',
                                            width: '100%',
                                            height: '100%',
                                            color: 'inherit',
                                            lineHeight: WIDE
                                        }}
                                        onClick={e => e.preventDefault()}
                                    >
                                        {i.toString(16).toUpperCase() + j.toString(16).toUpperCase()}
                                    </a>
                                </Cell>
                            ))}
                        </Fragment>
                    </tr>
                ))}
            </tbody>
        </table>
    );
};

export default HighscoreOverview;
