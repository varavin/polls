function PollCreationForm(wrapper)
{
    this.wrapper = wrapper;
    this.apiRequest = new APIRequest();
    this.buttonStart = this.wrapper.getElementsByClassName('jsStartPoll')[0];
    this.buttonAddAnswer = this.wrapper.getElementsByClassName('jsAddAnswer')[0];
    this.answersRows = this.wrapper.getElementsByClassName('jsAnswerRow');
    this.errorMessage = this.wrapper.getElementsByClassName('jsErrorMessage')[0];
    this.questionInput = this.wrapper.getElementsByClassName('jsQuestionInput')[0];
    this.answersInputs = this.wrapper.getElementsByClassName('jsAnswerInput');

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
        node.innerHTML = '<th>Answer ' + (self.answersRows.length + 1) + ':</th><td><input type="text" value="No" class="input-text jsAnswerInput" /></td>';
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