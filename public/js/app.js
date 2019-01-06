function APIRequest()
{
    this.send = function(method, url, payload, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open(method, '/api/' + url, false);
        xhr.onload = function() { callback(JSON.parse(xhr.response)); };
        xhr.send(JSON.stringify(payload));
    }
}

function PollCreationForm(apiRequest)
{
    this.apiRequest = apiRequest;
    this.buttonStart = document.getElementById('startPoll');
    this.buttonAddAnswer = document.getElementById('addAnswer');
    this.answersRows = document.getElementsByClassName('jsAnswerRow');
    this.errorMessage = document.getElementById('errorMessage');
    this.questionInput = document.getElementById('questionInput');
    this.answersInputs = document.getElementsByClassName('jsAnswerInput');

    this.startPoll = function () {
        var self = this;
        var payload = {
            question: self.questionInput.value,
            answers: []
        };
        [].forEach.call(self.answersInputs, function(input){
            payload.answers.push({text: input.value});
        });
        self.apiRequest.send('POST', 'poll', payload, function(resp){
            if (resp.success === false) {
                self.showError(resp.message);
            }
            if (resp.data.uid) {
                document.location = '/poll/' + resp.data.uid;
            }
        })
    };

    this.addAnswer = function() {
        var self = this;
        var node = document.createElement('tr');
        node.className = 'jsAnswerRow';
        node.innerHTML = '<th>Answer ' + (self.answersRows.length + 1) + ':</th><td><input type="text" value="No" class="input-text jsAnswerText" /></td>';
        var lastRow = self.answersRows.item(self.answersRows.length - 1);
        lastRow.parentNode.insertBefore(node, lastRow.nextSibling);
    };

    this.showError = function(message) {
        this.errorMessage.innerHTML = message;
    };

    this.init = function() {
        var self = this;
        self.buttonStart.addEventListener('click', function(){ self.startPoll(); });
        self.buttonAddAnswer.addEventListener('click', function(){ self.addAnswer(); });
    };

    this.init();
}

document.addEventListener('DOMContentLoaded', function() {
    var form = new PollCreationForm(new APIRequest());
});