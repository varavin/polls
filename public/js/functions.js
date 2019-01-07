function setCookie(name,value) {
    var expires = "";
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0) ===' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name+'=; Max-Age=-99999999;';
}

function APIRequest()
{
    this.send = function(method, url, payload, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open(method, window.jsConfig.siteRootURL + '/api/' + url, false);
        xhr.onload = function() { callback(JSON.parse(xhr.response)); };
        xhr.send(JSON.stringify(payload));
    }
}