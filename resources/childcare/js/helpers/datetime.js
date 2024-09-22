export const validateHhMm = (value) => {
    var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(value);
    return isValid;
}
export const validateYYMMDD = (value) => {
    var isValid = /^\d{4}-\d{2}-\d{2}$/.test(value);
    return isValid;
}
export const changeToHhMm = (value) => {
    if(value) {
        var hour = value.split(':')[0];
        var min = value.split(':')[1];
        if(hour && hour < 24)
            hour = ('0' + hour).slice(-2);
        if(min && min < 60)
            min = ('0' + min).slice(-2);
        return hour + ":" + min;
    }
    return null;
}
