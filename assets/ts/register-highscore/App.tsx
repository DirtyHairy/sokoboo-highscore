/** @jsx jsx */

import { render } from 'react-dom';

import { jsx } from '@emotion/core';

import Scores from '../component/Scores';

function main() {
    const root = document.getElementById('react-root');
    if (!root) {
        return;
    }

    const levelAttr = root.dataset.level;
    if (!levelAttr) {
        return;
    }

    const level = parseInt(levelAttr, 10);
    if (isNaN(level)) {
        return;
    }

    const rank = root.dataset.rank ? parseInt(root.dataset.rank, 10) : undefined;

    render(<Scores level={level} highlightRank={rank} css={{ margin: 'auto' }} />, root);
}

main();
