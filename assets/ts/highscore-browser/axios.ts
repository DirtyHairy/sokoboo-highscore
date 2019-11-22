import Axios, { AxiosAdapter, AxiosInstance } from 'axios';
import { setupCache } from 'axios-cache-adapter';

export function setupAxios(): AxiosInstance {
    const axiosCache = setupCache({
        maxAge: 1000 * 15 * 60
    });

    const adapter: AxiosAdapter = config =>
        new Promise((resolve, reject) => {
            if (config.cancelToken) {
                config.cancelToken.promise.then(cancel => {
                    reject(cancel);
                });
            }

            const { cancelToken, ..._config } = config;

            axiosCache.adapter(_config as any).then(
                result => resolve(result as any),
                error => reject(error)
            );
        });

    return Axios.create({
        adapter
    });
}
