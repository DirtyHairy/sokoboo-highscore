import '../../scss/play.scss';

import Stellerator from '6502.ts/lib/src/web/embedded/stellerator/Stellerator';

import { applyStripes } from '../stripes/stripes';
import sokobooRom from './sokoboo';

const stelleratorWorker: string = require('!file-loader!6502.ts/dist/worker/stellerator.min.js');

async function main(): Promise<void> {
    const stellerator = new Stellerator(
        document.getElementById('emulation-canvas') as HTMLCanvasElement,
        stelleratorWorker
    );

    stellerator.enableSmoothScaling(false).enablePovSimulation(true);

    await stellerator.start(sokobooRom, Stellerator.TvMode.ntsc);

    stellerator
        .getControlPanel()
        .difficultyPlayer2()
        .toggle(false);

    await stellerator.resume();
}

applyStripes();
main();
