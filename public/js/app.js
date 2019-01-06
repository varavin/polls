document.addEventListener('DOMContentLoaded', function() {
    var elem = null;
    if (elem = document.getElementById('pollCreationForm')) {
        var pollCreationForm = new PollCreationForm(new APIRequest());
    }
    if (elem = document.getElementById('pollVotingForm')) {
        var pollVotingForm = new PollVotingForm(new APIRequest());
    }
});