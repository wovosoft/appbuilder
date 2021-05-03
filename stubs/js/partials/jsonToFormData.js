(function (root, factory) {
    // root is undefined in a Webpack bundle
    if (!root) {
        root = {};
    }

    if (typeof define === 'function' && define.amd) {
        define([], () => (root.jsonToFormData = factory()));
    } else if (typeof module === 'object' && module.exports) {
        module.exports = (root.jsonToFormData = factory());
    } else {
        root.jsonToFormData = factory();
    }
}(this, () => {
    const mergeObjects = (object1, object2) => [object1, object2].reduce((carry, objectToMerge) => {
        Object.keys(objectToMerge).forEach((objectKey) => carry[objectKey] = objectToMerge[objectKey]);
        return carry;
    }, {});

    const isArray = val => Array.isArray(val);
    const isJsonObject = (val) => !isArray(val) && typeof val === 'object' && !!val && !(val instanceof Blob) && !(val instanceof Date);
    const isAppendFunctionPresent = (formData) => typeof formData.append === 'function';
    const isGlobalFormDataPresent = () => typeof FormData === 'function';

    const getDefaultFormData = () => isGlobalFormDataPresent() ? new FormData() : null;

    function convert(jsonObject, options) {
        if (options && options.initialFormData) {
            if (!isAppendFunctionPresent(options.initialFormData)) {
                throw 'initialFormData must have an append function.';
            }
        } else if (!isGlobalFormDataPresent()) {
            throw 'This environment does not have global form data. options.initialFormData must be specified.';
        }

        let defaultOptions = {
            initialFormData: getDefaultFormData(),
            showLeafArrayIndexes: true,
            includeNullValues: false,
            mapping: function (value) {
                if (typeof value === 'boolean') {
                    return +value ? '1' : '0';
                }
                return value;
            }
        };

        let mergedOptions = mergeObjects(defaultOptions, options || {});

        return convertRecursively(jsonObject, mergedOptions, mergedOptions.initialFormData);
    }

    function convertRecursively(jsonObject, options, formData, parentKey) {
        let index = 0;
        for (let key in jsonObject) {
            if (jsonObject.hasOwnProperty(key)) {
                let propName = parentKey || key;
                let value = options.mapping(jsonObject[key]);
                if (parentKey && isJsonObject(jsonObject)) {
                    propName = parentKey + '[' + key + ']';
                }

                if (parentKey && isArray(jsonObject)) {
                    if (isArray(value) || options.showLeafArrayIndexes) {
                        propName = parentKey + '[' + index + ']';
                    } else {
                        propName = parentKey + '[]';
                    }
                }

                if (isArray(value) || isJsonObject(value)) {
                    convertRecursively(value, options, formData, propName);
                } else if (value instanceof FileList) {
                    for (let j = 0; j < value.length; j++) {
                        formData.append(propName + '[' + j + ']', value.item(j));
                    }
                } else if (value instanceof Blob) {
                    formData.append(propName, value, value.name);
                } else if (value instanceof Date) {
                    formData.append(propName, value.toISOString());
                } else if (((value === null && options.includeNullValues) || value !== null) && value !== undefined) {
                    formData.append(propName, value);
                }
            }
            index++;
        }
        return formData;
    }

    return convert;
}));
