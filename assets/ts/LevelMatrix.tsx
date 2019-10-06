/* @jsx jsx */

import useAxios from 'axios-hooks';
import { FunctionComponent } from 'react';

import { jsx } from '@emotion/core';

import Matrix from './matrix/Matrix';
import LevelStatistics from './model/LevelStatistics';

const LevelMatrix: FunctionComponent = () => {
    const [{ data, loading, error }] = useAxios<Array<LevelStatistics>>('/api/statistics');

    if (error) {
        return <div>ERROR!</div>;
    }

    if (loading) {
        return <div>Loading...</div>;
    }

    return <Matrix statistics={data}/>;
};

export default LevelMatrix;
