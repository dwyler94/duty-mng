const required = (value) => {
    if (
        value === null ||
        value === undefined ||
        !value.toString().trim().length
    ) {
        return 'validation_empty_error';
    }
};

const maxLength = (value, length) => {
    if (!value) return;

    if (Array.isArray(value)) {
        if (value.length > length) return 'validation_max_length_error';
        return;
    }
    if (value.toString().trim().length > length) {
        return 'validation_max_length_error';
    }
};
const minLength = (value, length) => {
    if (!value) return;
    if (Array.isArray(value)) {
        if (value.length < length) return 'validation_min_length_error';
        return;
    }
    if (value.toString().trim().length < length) {
        return 'validation_min_length_error';
    }
};
const length = (value, length) => {
    if (!value) return;
    if (Array.isArray(value)) {
        if (value.length !== length) return 'validation_length_error';
        return;
    }
    if (value.toString().trim().length !== length) {
        return 'validation_length_error';
    }
};

const notEmpty = (value) => {
    if (!value) {
        return 'validation_not_empty_error';
    }
    if (value === '0') {
        return 'validation_not_empty_error';
    }
    if (Array.isArray(value)) {
        if (value.length === 0) return 'validation_not_empty_error';
    }
};

const email = (value) => {
    if (!value) return;
    const re =
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!re.test(value)) {
        return 'validation_email_type_error';
    }
};
const digits = (value) => {
    if (!value) return;
    const re = /^[0-9]+$/;
    if (!re.test(value)) {
        return 'validation_digits_error';
    }
};

const inArray = (value, array) => {
    if (!value) return;
    if (!array.includes(`${value}`)) {
        return 'validation_not_in_array';
    }
};

const validators = {
    required,
    maxLength,
    minLength,
    email,
    notEmpty,
    inArray,
    length,
    digits,
};

export default validators;

export const applyValidation = (rule, value) => {
    // check custom error message
    const segs = rule.split('|');
    rule = segs[0];

    const ruleSegs = rule.split(':');
    const ruleName = ruleSegs[0];
    if (!ruleName) return;

    let result;

    if (ruleName === 'required') {
        result = required(value);
    }
    if (ruleName === 'maxLength') {
        const length = ruleSegs[1];
        result = maxLength(value, parseInt(length));
    }
    if (ruleName === 'minLength') {
        const length = ruleSegs[1];
        result = minLength(value, parseInt(length));
    }
    if (ruleName === 'length') {
        const len = ruleSegs[1];
        result = length(value, parseInt(len));
    }
    if (ruleName === 'email') {
        result = email(value);
    }
    if (ruleName === 'notEmpty') {
        result = notEmpty(value);
    }
    if (ruleName === 'inArray') {
        const array = ruleSegs[1].split(',');
        result = inArray(value, array);
    }
    if (ruleName === 'digits') {
        result = digits(value);
    }
    if (result && segs[1]) {
        return segs[1];
    }
    return result;
};

export const extractValiationParams = (rules) => {
    let params = {};
    if (!rules) return params;

    for (const rule of rules) {
        if (!rule) continue;
        const segs = rule.split('|');
        const ruleSeg = segs[0];

        const ruleSegs = ruleSeg.split(':');
        const ruleName = ruleSegs[0];
        if (ruleName === 'maxLength') {
            const value = ruleSegs[1];
            params.maxLength = value;
        }
        if (ruleName === 'minLength') {
            const value = ruleSegs[1];
            params.minLength = value;
        }
        if (ruleName === 'length') {
            const value = ruleSegs[1];
            params.length = value;
        }
    }
    return params;
};
