import '../scss/highscores.scss';

import Axios from 'axios';
import { configure as configureAxios } from 'axios-hooks';
import LRUCache from 'lru-cache';
import React, { FunctionComponent } from 'react';
import { render } from 'react-dom';
import { BrowserRouter as Router, Route } from 'react-router-dom';

import App from './App';
import LevelMatrix from './LevelMatrix';

const root = document.getElementById('react-root');

const axios = Axios.create();
const cache = new LRUCache({ max: 300 });
configureAxios({ axios, cache });

function parseLevel(level: string | undefined): number | undefined {
    if (!level) {
        return undefined;
    }

    const result = parseInt(level, 10);

    return isNaN(result) ? undefined : result;
}

const Routing: FunctionComponent = () => (
    <Router>
        <Route
            exact
            path="/highscores/:level?"
            render={({ match }) => <App level={parseLevel(match.params.level)} />}
        />
    </Router>
);

render(<Routing />, root);
