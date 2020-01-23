import '../../scss/play.scss';

import Stellerator from '6502.ts/lib/src/web/embedded/stellerator/Stellerator';

import { applyStripes } from '../stripes/stripes';
import sokobooRom from './sokoboo';

const stelleratorWorker: string = require('!file-loader!6502.ts/dist/worker/stellerator.min.js');

async function main(): Promise<void> {
    const stellerator = new Stellerator(
        document.getElementById('emulation-canvas') as HTMLCanvasElement,
        stelleratorWorker,
        {
            smoothScaling: false,
            simulatePov: true,
            volume: 0.1
        }
    );

    stellerator.dataTapMessage.addHandler(data => {
        let base = 1;
        let code = 0;

        for (let i = data.length - 1; i >= 0; i--) {
            code += data[i] * base;
            base *= 256;
        }

        console.log(`received code ${code}`);
    });

    await stellerator.start(sokobooRom, Stellerator.TvMode.ntsc, {
        dataTap: true,
        cpuAccuracy: Stellerator.CpuAccuracy.instruction
    });

    stellerator
        .getControlPanel()
        .difficultyPlayer2()
        .toggle(false);

    await stellerator.resume();
}

applyStripes();
main();
