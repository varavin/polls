function PollVotingForm(wrapper, apiRequest, webSocketURL, answersIds, results)
{
    this.wrapper = wrapper;
    this.apiRequest = apiRequest;
    this.conn = new WebSocket(webSocketURL);
    this.answersIds = JSON.parse(answersIds);
    this.results = JSON.parse(results);
    this.voteForm = this.wrapper.getElementsByClassName('jsVoteForm')[0];
    this.buttonVote = this.wrapper.getElementsByClassName('jsButtonVote')[0];
    this.errorMessage = this.wrapper.getElementsByClassName('jsErrorMessage')[0];
    this.pollUid = this.wrapper.getElementsByClassName('jsPollUid')[0].value;
    this.payload = {
        userUid: getCookie('uid'),
        name: '',
        answerId: 0
    };

    this.buttonVoteHandler = function() {
        var self = this;
        if (!self.preparePayload()) {
            return false;
        }
        if (self.payload.userUid) {
            self.sendVote(self.payload);
            return true;
        }
        self.apiRequest.send('POST', 'user', {}, function(resp) {
            if (resp.success && resp.data.uid) {
                setCookie('uid', resp.data.uid);
                self.payload.userUid = resp.data.uid;
                self.sendVote(self.payload);
            }
        });
    };

    this.preparePayload = function() {
        var self = this;
        var selectedRadio = self.wrapper.querySelector('input[name="answerRadio"]:checked');
        if (selectedRadio) {
            self.payload.answerId = selectedRadio.value;
        } else {
            self.showError('No answer selected. Please choose one.');
            return false;
        }

        self.payload.name = document.getElementById('visitorName').value;
        if (!self.payload.name) {
            self.showError('Visitor name is missing.');
            return false;
        }
        return true;
    };

    this.sendVote = function(payload) {
        var self = this;
        self.apiRequest.send('POST', 'vote', payload, function(resp) {
            if (resp.success) {
                self.conn.send(JSON.stringify({
                    command: 'message',
                    message: 'results for poll ' + self.pollUid + ' updated'
                }));
                self.renderResults(resp.data);
                self.voteForm.remove();
            } else {
                self.showError(resp.message);
                return false;
            }
        })
    };

    this.renderResults = function(results) {
        var self = this;
        var tableBody = self.wrapper.getElementsByClassName('jsResults')[0];
        var html = '';
        [].forEach.call(results, function(row){
            html += '<tr>';
            html += '<td>' + row.visitorName + '</td>';
            for(var i = 0; i < self.answersIds.length; i++) {
                html += '<td>';
                html += (self.answersIds.indexOf(parseInt(row.answerId)) === i) ? 'x' : '';
                html += '</td>';
            }
            html += '</tr>';
        });
        tableBody.innerHTML = html;
    };

    this.showError = function(message) {
        this.errorMessage.innerHTML = message;
    };

    this.init = function() {
        var self = this;
        self.renderResults(self.results);
        self.buttonVote.addEventListener('click', function() {
            self.buttonVoteHandler();
        });
        self.conn.onopen = function(resp) {
            console.log('Connection established!');
            self.conn.send(JSON.stringify({command: 'subscribe', channel: self.pollUid}));
        };
        self.conn.onmessage = function(resp) {
            self.renderResults(JSON.parse(resp.data));
        };
    };

    this.init();
}
