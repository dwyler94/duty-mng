export const validatePassword = (value) => {
    var isValid = 	/^[0-9a-zA-Z]*$/.test(value);
    return isValid;
}
