import { NTSC } from './palette';

function applyStripes() {
    const canvas = document.createElement('canvas');
    canvas.width = 4;
    canvas.height = 512;

    const ctx = canvas.getContext('2d');
    if (!ctx) {
        return;
    }

    ctx.globalAlpha = 0.15;

    for (let i = 0; i < 800; i += 4) {
        ctx.beginPath();
        ctx.rect!(0, i, 4, 2);

        const color = NTSC[(Math.floor(Math.random() * 0x10) << 3) | 0x1];
        ctx.strokeStyle = ctx.fillStyle = color;

        ctx.stroke();
        ctx.fill();
    }

    const style = document.documentElement.style;

    style.backgroundImage = `url(${canvas.toDataURL()})`;
    style.backgroundRepeat = 'repeat';
}

applyStripes();
