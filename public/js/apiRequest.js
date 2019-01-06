function APIRequest()
{
    this.send = function(method, url, payload, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open(method, window.jsConfig.siteRootURL + '/api/' + url, false);
        xhr.onload = function() { callback(JSON.parse(xhr.response)); };
        xhr.send(JSON.stringify(payload));
    }
}
