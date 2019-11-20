import { NTSC } from './palette';

const LINE_HEIGHT = 3;
const CANVAS_WIDTH = 2;

export function applyStripes() {
    const canvas = document.createElement('canvas');
    canvas.width = CANVAS_WIDTH;
    canvas.height = 3 * LINE_HEIGHT;

    const ctx = canvas.getContext('2d');
    if (!ctx) {
        return;
    }

    const color = NTSC[(Math.floor(Math.random() * 0x10) << 3) | 0x2];

    ctx.strokeStyle = ctx.fillStyle = color;
    ctx.globalAlpha = 0.35;
    ctx.shadowBlur = 10;
    ctx.shadowColor = color;
    ctx.shadowOffsetY = LINE_HEIGHT;

    ctx.fillRect(0, LINE_HEIGHT, CANVAS_WIDTH, LINE_HEIGHT);

    const style = document.documentElement.style;

    style.backgroundImage = `url(${canvas.toDataURL()})`;
    style.backgroundRepeat = 'repeat';
}
