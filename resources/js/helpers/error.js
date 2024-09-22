import LocalStorage from "./localStorage";

export const formatError = (e) => {
    if (!e) return '';
    if (e.message) {
        return e.message;
    }
    let msg = String(e);
    if (msg === '[object Object') {
        msg = 'エラーが発生しました';
    }
    return msg;
};
export const handleSignOut = () => {
    LocalStorage.removeToken();
    window.location.href = "/login";
}
export const showError = (error) => {
    if (!error) return;
    if (Array.isArray(error)) {
        return error.forEach(item => showError(item));
    }
    if (error instanceof Object) {
        let message = error.message;

        if (error?.message === 'Network request failed') {
            message = 'ネットワーク要求が失敗しました';
        }
        if (error.name === 'SyntaxError') {
            message = 'エラーが発生しました';
        }
        if (error?.code === 'Too Many Requests') {
            message =
                '現在のサーバーが要求を実行できません。後に再度お試しください';
        }
        if (error?.code === 'Internal Server Error') {
            // if (!__DEV__) {
            message = 'サーバーでエラーが発生しました';
            // }
        }
        if (error.message === 'Request failed with status code 401') {
            message = 'Unauthorized';
        }
        window.Toast.fire({
            icon: 'error',
            title: message
        })
    } else {
        window.Toast.fire({
            icon: 'error',
            title: error
        })
    }
}

export const showSuccess = (message) => {
    window.Toast.fire({
        icon: 'success',
        title: message
    })
}
