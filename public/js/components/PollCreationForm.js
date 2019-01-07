function PollCreationForm(wrapper, apiRequest)
{
    this.wrapper = wrapper;
    this.apiRequest = apiRequest;
    this.buttonStart = this.wrapper.getElementsByClassName('jsStartPoll')[0];
    this.buttonAddAnswer = this.wrapper.getElementsByClassName('jsAddAnswer')[0];
    this.answersRows = this.wrapper.getElementsByClassName('jsAnswerRow');
    this.errorMessage = this.wrapper.getElementsByClassName('jsErrorMessage')[0];
    this.questionInput = this.wrapper.getElementsByClassName('jsQuestionInput')[0];
    this.answersInputs = this.wrapper.getElementsByClassName('jsAnswerInput');
    this.payload = {
        question: this.questionInput.value,
        answers: []
    };

    this.buttonStartHandler = function () {
        var self = this;
        if (!self.preparePayload()) {
            return false;
        }
        self.apiRequest.send('POST', 'poll', self.payload, function(resp){
            if (resp.success) {
                document.location = '/poll/' + resp.data.uid;
            } else {
                self.showError(resp.message);
            }
        })
    };

    this.preparePayload = function () {
        var self = this;
        [].forEach.call(self.answersInputs, function(input){
            self.payload.answers.push({text: input.value});
        });
        self.payload.question = this.questionInput.value;
        if (!self.payload.question) {
            self.showError('Question text is missing');
            return false;
        }
        return true;
    };

    this.buttonAddAnswerHandler = function() {
        var self = this;
        var node = document.createElement('tr');
        node.className = 'jsAnswerRow';
        node.innerHTML = '<th>Answer ' + (self.answersRows.length + 1) + ':</th><td><input type="text" value="No" class="jsAnswerInput input-text" /></td>';
        var lastRow = self.answersRows.item(self.answersRows.length - 1);
        lastRow.parentNode.insertBefore(node, lastRow.nextSibling);
    };

    this.showError = function(message) {
        this.errorMessage.innerHTML = message;
    };

    this.init = function() {
        var self = this;
        self.buttonStart.addEventListener('click', function(){
            self.buttonStartHandler();
        });
        self.buttonAddAnswer.addEventListener('click', function(){
            self.buttonAddAnswerHandler();
        });
    };

    this.init();
}