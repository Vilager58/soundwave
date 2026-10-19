class ApiClient {
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
    }

    async request(endpoint, options = {}) {
        let queryString = '';
        if (options.params && typeof options.params === 'object') {
            queryString = '?' + new URLSearchParams(options.params).toString();
        }

        const url = `${this.baseUrl}${endpoint}${queryString}`;

        const headers = {
            'Content-Type': 'application/json',
            ...options.headers,
        };

        if (options.body && typeof options.body === 'object') {
            options.body = JSON.stringify(options.body);
            
        }

        try {
            const response = await fetch(url, {
                ...options,
                headers
            });

            if (!response.ok) {
                throw new Error(`Ошибка !Связь: ${response.status} ${response.statusText}`);
            }

            return await response.json();
        } catch (error) {
            console.error(`Ошибка при запросе к ${url}:`, error);
            throw error;
        }
    }

    get(endpoint, params, options) {
        return this.request(endpoint, {
            ...options,
            params,
            method: 'GET'
        });
    }
    post(endpoint, body, options) {
        return this.request(endpoint, {
            ...options,
            method: 'POST',
            body
        });
    }
    put(endpoint, body, options) {
        return this.request(endpoint, {
            ...options,
            method: 'PUT',
            body
        });
    }
    delete(endpoint, options) {
        return this.request(endpoint, {
            ...options,
            method: 'DELETE'
        });
    }
}

export const api = new ApiClient('http://soundwave.test/api/v1');