function PollVotingForm(wrapper)
{
    this.wrapper = wrapper;
    this.apiRequest = new APIRequest();
    this.buttonVote = this.wrapper.getElementsByClassName('jsButtonVote')[0];
    this.errorMessage = this.wrapper.getElementsByClassName('jsErrorMessage')[0];
    this.pollUid = this.wrapper.getElementsByClassName('jsPollUid')[0].value;
    this.conn = new WebSocket('ws://localhost:8888');

    this.vote = function() {
        var self = this;
        var payload = {
            userUid: '',
            name: '',
            answerId: 0
        };

        var selectedRadio = document.querySelector('input[name="answerRadio"]:checked');
        if (selectedRadio) {
            payload.answerId = selectedRadio.value;
        } else {
            self.showError('No answer selected. Please choose one.');
            return;
        }

        payload.name = document.getElementById('visitorName').value;
        if (!payload.name) {
            self.showError('Visitor name is missing.');
            return;
        }

        payload.userUid = getCookie('uid');

        if (payload.userUid) {
            self.sendVote(payload);
        } else {
            self.apiRequest.send('POST', 'user', {}, function(resp) {
                if (resp.success && resp.data.uid) {
                    setCookie('uid', resp.data.uid);
                    payload.userUid = resp.data.uid;
                    self.sendVote(payload);
                }
            });
            return true;
        }
    };

    this.sendVote = function(payload) {
        var self = this;
        self.apiRequest.send('POST', 'vote', payload, function(resp) {
            if (resp.success) {
                self.conn.send(JSON.stringify({
                    command: 'message',
                    message: 'results for poll ' + self.pollUid + ' updated'
                }));
                window.location.reload();
            } else {
                self.showError(resp.message);
                return false;
            }
        })
    };

    this.updateResults = function (data) {
        var self = this;
        var resultsTable = document.getElementsByClassName('jsResults')[0];
        console.log(data);
    };

    this.showError = function(message) {
        this.errorMessage.innerHTML = message;
    };


    this.init = function() {
        var self = this;
        self.buttonVote.addEventListener('click', function() { self.vote(); });
        self.conn.onopen = function(e) {
            console.log('Connection established!');
            self.conn.send(JSON.stringify({command: "subscribe", channel: self.pollUid}));
        };
        self.conn.onmessage = function(e) {
            self.updateResults(JSON.parse(e.data));
        };
    };

    this.init();
}
