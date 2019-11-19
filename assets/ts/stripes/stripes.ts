import { NTSC } from './palette';

function applyStripes() {
    const canvas = document.createElement('canvas');
    canvas.width = 4;
    canvas.height = 512;

    const ctx = canvas.getContext('2d');
    if (!ctx) {
        return;
    }

    for (let i = 0; i < 800; i += 4) {
        ctx.beginPath();
        ctx.rect!(0, i, 4, 2);
        ctx.globalAlpha = 0.15;

        const color = NTSC[(Math.floor(Math.random() * 0x10) << 3) | 0x1];
        console.log(color);
        ctx.strokeStyle = color;
        ctx.fillStyle = color;

        ctx.stroke();
        ctx.fill();
    }

    const stripes = canvas.toDataURL();
    const style = document.documentElement.style;
    style.backgroundImage = `url(${stripes})`;
    style.backgroundRepeat = 'repeat';
}

applyStripes();
