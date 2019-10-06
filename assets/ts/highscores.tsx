import '../scss/highscores.scss';

import Axios from 'axios';
import { configure as configureAxios } from 'axios-hooks';
import LRUCache from 'lru-cache';
import React from 'react';
import { render } from 'react-dom';

import HighscoreOverview from './LevelMatrix';

const root = document.getElementById('react-root');

const axios = Axios.create();
const cache = new LRUCache({ max: 300 });
configureAxios({ axios, cache });

render(<HighscoreOverview />, root);
