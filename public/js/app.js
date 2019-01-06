
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

    this.buttonStart = null;

    this.init = function() {
        var self = this;
        self.buttonStart = document.getElementById('startPoll');
        self.questionTextInput = document.getElementById('questionText');
        self.buttonStart.addEventListener('click', function(){ self.startPoll(); });

    };

    this.startPoll = function () {
        var self = this;
        var answersInputs = document.getElementsByClassName('jsAnswerText');
        var answers = [];
        [].forEach.call(answersInputs, function(input){
            answers.push({text: input.value});
        });
        var payload = {
            question: "qqqqqq",
            answers: answers
        };
        self.apiRequest.send('POST', 'poll', payload, function(resp){
            if (!resp.success) return;
            if (resp.data.uid) {
                document.location = '/poll/' + resp.data.uid;
            }
        })
    };
}

function domContentLoaded() {
    var form = new PollCreationForm(new APIRequest());
    form.init();
}


document.addEventListener('DOMContentLoaded', domContentLoaded);
