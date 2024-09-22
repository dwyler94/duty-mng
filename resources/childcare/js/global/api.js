import { formatError, handleSignOut, showError } from "../helpers/error";
import LocalStorage from "../helpers/localStorage";
import { camelCaseKeys, snakeCaseKeys } from "../helpers/object";
import { getAbsoluteUrl, getUrlWithParam } from "../helpers/url";

const baseUrl = process.env.MIX_CHILD_API_BASE_URL;

const precessHeaders = (headers) => {
    if (!headers) headers = {};

    headers = {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...headers,
    };

    const token = LocalStorage.getToken();
    if (token) {
        headers['Authorization'] = 'Bearer ' + token;
    }
    return headers;
};

const requestErrorHandler = (e, cb) => {
    if (
        e.response &&
        e.response.data &&
        e.response.data.errors &&
        !_.isEmpty(e.response.data.errors)
    ) {
        let errors = [];
        for (const errorField in e.response.data.errors) {
            errors.push(e.response.data.errors[errorField]);
        }
        cb({ message: errors.join('<br/>') });
    } else if (e.response && e.response.data && e.response.data.message) {
        cb({ message: e.response.data.message, status: e.response.status });
    } else {
        cb(e);
    }
};

const apiWrapper = (method, url, headers, data) => {
    return new Promise(async (resolve, reject) => {
        let postHeader = precessHeaders(headers);
        if (data instanceof Object && !(data instanceof FormData)) {
            data = _.omit(data, 'errors');
        }
        let absUrl = getAbsoluteUrl(url, baseUrl);
        if (method === 'POST' || method === 'PUT') {
            if (!(data instanceof FormData)) {
                data = snakeCaseKeys(data);
            }
        } else {
            data = snakeCaseKeys(data);
            absUrl = getUrlWithParam(absUrl, data);
        }

        try {
            let response = null;
            if (method === 'GET') {
                response = await window.axios.get(absUrl, { headers: postHeader });
            } else if (method === 'POST') {
                response = await window.axios.post(absUrl, data, {
                    headers: postHeader,
                });
            } else if (method === 'PUT') {
                response = await window.axios.put(absUrl, data, {
                    headers: postHeader,
                });
            } else {
                response = await window.axios.delete(absUrl, { headers: postHeader });
            }
            if (response.status === 401) {
                handleSignOut();
                return true;
            }
            if (!response.data.success) {
                reject({
                    message: formatError(response.data),
                    code: response.status,
                });
            } else {
                resolve(camelCaseKeys(response.data.data));
            }
        } catch (e) {
            requestErrorHandler(e, reject)
        }
    })
}

export const apiErrorHandler = (error) => {
    requestErrorHandler(error, (e) => {
        if ((e.response && e.response.status === 401) || (e.status === 401)) {
            handleSignOut();
            return;
        }
        showError(e);
    })
}
const api = {
    get: (url, headers = null, data = {}) => {
        return apiWrapper('GET', url, headers, data);
    },
    post: (url, headers = null, data = {})  => {
        return apiWrapper('POST', url, headers, data);
    },
    put: (url, headers = null, data = {}) => {
        return apiWrapper('PUT', url, headers, data);
    },
    delete: (url, headers = null, data = {}) => {
        return apiWrapper('DELETE', url, headers, data);
    }
}
export default api;
