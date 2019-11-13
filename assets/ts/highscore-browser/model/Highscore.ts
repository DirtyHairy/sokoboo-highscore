interface Highscore {
    level: number;
    rank: number;
    nick: string;
    moves: number;
    seconds: number;
    timestamp: number;
}

export function formatSeconds(seconds: number): string {
    const hours = Math.floor(seconds / 3600);
    seconds %= 3600;

    const minutes = Math.floor(seconds / 60);
    seconds %= 60;

    const pad = (x: number): string => x.toString().padStart(2, '0');

    return hours ? `${pad(hours)}:${pad(minutes)}:${pad(seconds)}` : `${pad(minutes)}:${pad(seconds)}`;
}

export function formatTimestamp(timestamp: number): string {
    const date = new Date(timestamp * 1000);

    return (
        date
            .getMonth()
            .toString()
            .padStart(2, '0') +
        '/' +
        date
            .getDate()
            .toString()
            .padStart(2, '0') +
        '/' +
        date.getFullYear()
    );
}

export default Highscore;
