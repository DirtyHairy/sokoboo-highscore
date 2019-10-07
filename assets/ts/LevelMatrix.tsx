/* @jsx jsx */

import useAxios from 'axios-hooks';
import { number } from 'prop-types';
import { FunctionComponent } from 'react';

import { jsx } from '@emotion/core';

import Matrix from './matrix/Matrix';
import LevelStatistics from './model/LevelStatistics';

export interface Props {
    selectedLevel: number | undefined;
}

const LevelMatrix: FunctionComponent<Props> = ({ selectedLevel }) => {
    const [{ data, loading, error }] = useAxios<Array<LevelStatistics>>('/api/statistics');

    if (error) {
        return <div>ERROR!</div>;
    }

    if (loading) {
        return <div>Loading...</div>;
    }

    return <Matrix statistics={data} selectedLevel={selectedLevel} />;
};

export default LevelMatrix;
