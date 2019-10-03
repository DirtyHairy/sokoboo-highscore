import '../scss/index.scss';

import React, { FunctionComponent } from 'react';
import { render } from 'react-dom';

const App: FunctionComponent = () => <h1>Sokoboo highscore registry</h1>;

const root = document.getElementById('react-root');

render(<App/>, root);
