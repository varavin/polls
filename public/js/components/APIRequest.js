function APIRequest(apiUrl)
{
    this.apiUrl = apiUrl;
    this.send = function(method, url, payload, callback) {
        var self = this;
        var xhr = new XMLHttpRequest();
        xhr.open(method, self.apiUrl + url);
        xhr.onload = function() { callback(JSON.parse(xhr.response)); };
        xhr.send(JSON.stringify(payload));
    }
}