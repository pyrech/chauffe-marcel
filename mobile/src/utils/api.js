export async function checkStatus(response) {
    if (response.status !== 200) {
        if (response.status === 400 && isJson(response)) {
            const json = await response.json();
            throw new Error(json.message);
        }
        if (response.status === 401) {
            throw new Error('Authentication failed (check the apiKey)');
        }
        if (response.status === 404) {
            throw new Error('Authentication failed (check the host)');
        }
        throw new Error('Response failed (code = ' + response.status + ')');
    }

    return response;
}

export function isJson(response) {
    var contentType = response.headers.get('content-type');
    return contentType && contentType.indexOf('application/json') !== -1;
}

export function toJson(response) {
    if (!isJson(response)) {
        throw new Error('Invalid response from the server');
    }

    return response.json();
}
