import '../scss/index.scss';

import React, { FunctionComponent } from 'react';
import { render } from 'react-dom';

const App: FunctionComponent = () => <div style={{ textAlign: 'center' }}>Sokoboo highscore registry</div>;

const root = document.getElementById('react-root');

render(<App/>, root);
