/**
 * Set cookie to root path, default expire time is 30 days
 * @param name
 * @param value
 * @param days
 */
function setCookie(name, value, days) {
    days = (value === null) ? 0 : (days || 30);
    let date = new Date();
    let time = !!days ? (date.getTime() + days * 24 * 60 * 60 * 1000) : (date.getTime() - 1);
    date.setTime(time);
    document.cookie = name + "=" + encodeURIComponent(value) + ";expires=" + date.toUTCString() + ";path=/";
}

function getCookie(name) {
    let data = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
    return !!data ? decodeURIComponent(data[2]) : '';
}

function deleteCookie(name) {
    setCookie(name, null);
}

/**
 * @param {Object} file: native object of <input type="file"/>
 * @return {Object} File: {name, type, size, lastModified, lastModifiedDate, ...}
 */
function fileInfo(file) {
    return file ? (file.files ? file.files[0] : {}) : {}
}

function isJsonObject(data) {
    return Object.prototype.toString.call(data) === "[object Object]";
}

function isJsonString(data) {
    try {
        return JSON.parse(data);
    } catch (e) {
        return false;
    }
}

function generalCallback() {
    for (let i = 0; i < arguments.length; i++) {
        let arg = arguments[i];
        alert((typeof arg === "object") ? (isJsonObject(arg) ? JSON.stringify(arg) : Object.prototype.toString.call(arg)) : arg)
    }
    console.log(arguments);
}
