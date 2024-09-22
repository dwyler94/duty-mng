const APP_TOKEN_KEY = process.env.MIX_APP_TOKEN_KEY || 'lks_session';
const LOGIN_FAILURE_COUNT = process.env.LOGIN_FAILURE_COUNT || 'login_failure';
const LOGIN_TIME_STAMP = process.env.LOGIN_TIME_STAMP || 'login_failure_time';

const getToken = () => {
    const json = localStorage.getItem(APP_TOKEN_KEY);
    if (!json) {
        return null
    }
    try {
        return JSON.parse(json)
    } catch (e) {
        return json
    }
}
const saveToken = (token) => {
    if (token instanceof Object) {
        token = JSON.stringify(token);
    }
    localStorage.setItem(APP_TOKEN_KEY, token);
}
const removeToken = () => {
    saveToken(null)
}
const saveLoginFailure = () => {
    let count = getLoginFailure();
    localStorage.setItem(LOGIN_FAILURE_COUNT, ++count);
}
const getLoginFailure = () => {
    const loginCount = localStorage.getItem(LOGIN_FAILURE_COUNT);
    if(!loginCount) {
        return 0;
    }
    return loginCount;
}
const removeLoginFailure = () => {
    localStorage.removeItem(LOGIN_FAILURE_COUNT);
}
const saveLoginTimeStamp = () => {
    localStorage.setItem(LOGIN_TIME_STAMP, Date.now());
}
const getLoginTimeStamp = () => {
    const timeStamp = localStorage.getItem(LOGIN_TIME_STAMP);
    if(!timeStamp) {
        return Date.now();
    }
    return timeStamp;
}
const removeLoginTimeStamp = () => {
    localStorage.removeItem(LOGIN_TIME_STAMP);
}
const LocalStorage = {
    saveToken,
    getToken,
    removeToken,
    saveLoginFailure,
    getLoginFailure,
    removeLoginFailure,
    saveLoginTimeStamp,
    getLoginTimeStamp,
    removeLoginTimeStamp
}
export default LocalStorage;
