import '../stripes/stripes';

import { configure as configureAxios } from 'axios-hooks';
import LRUCache from 'lru-cache';
import React, { FunctionComponent } from 'react';
import { render } from 'react-dom';
import { BrowserRouter as Router, Route } from 'react-router-dom';

import App from './App';
import { setupAxios } from './axios';

const axios = setupAxios();
const cache = new LRUCache({ max: 1 });
configureAxios({ axios, cache });

const root = document.getElementById('react-root');

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
