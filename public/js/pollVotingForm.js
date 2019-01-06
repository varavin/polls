function PollVotingForm(apiRequest)
{
    this.apiRequest = apiRequest;
    this.buttonVote = document.getElementById('buttonVote');
    this.errorMessage = document.getElementById('errorMessage');

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
                window.location.reload();
            } else {
                self.showError(resp.message);
                return false;
            }
        })
    };

    this.showError = function(message) {
        this.errorMessage.innerHTML = message;
    };


    this.init = function() {
        var self = this;
        self.buttonVote.addEventListener('click', function() { self.vote(); });
    };

    this.init();
}
