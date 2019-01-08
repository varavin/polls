<?php
/**
 * @var \Polls\View $this
 * @var \Polls\Models\Poll $poll
 * @var \Polls\Models\Vote[] $results
 * @var string $websocketString
 * @var string $apiURL
 */

$this->addJsComponent("PollVotingForm", "
    var elem = null;
    if (elem = document.getElementsByClassName('jsComponentPollVotingForm')[0]) {
        jsComponentPollVotingForm = new PollVotingForm(
            elem, 
            new APIRequest('" . $apiURL . "'),
            '" . $websocketString . "',
            '" . json_encode($poll->getAnswersIds()) . "',
            '" . json_encode($poll->getResults()) . "'
        );
    }
");

?>
<div class="jsComponentPollVotingForm poll">
    <h1>
        <?= $poll->getQuestion() ?>
    </h1>

    <div class="ex2-question">
        <input type="hidden" class="jsPollUid" value="<?= $poll->getUid() ?>">
        <div class="ex2-question__label">
            Your name:
        </div>
        <div class="ex2-question__input">
            <input type="text" class="input-text" id="visitorName">
        </div>
        <div class="ex2-question__answer">
            <?php foreach ($poll->getAnswers() as $answer): /** @var \Polls\Models\Answer $answer */ ?>
                <label>
                    <input type="radio" name="answerRadio" value="<?= $answer->getId() ?>">
                    <?= $answer->getText() ?>
                </label>
            <?php endforeach ?>
        </div>
        <div class="ex2-question__submit">
            <input type="submit" class="jsButtonVote btn" value="Submit">
        </div>
        <div><p class="jsErrorMessage errorMessage"></p></div>
    </div>
    <h1>
        Results
    </h1>
    <br>
    <table class="ex2-table">
        <thead>
        <tr>
            <th>Name</th>
            <?php foreach ($poll->getAnswers() as $answer): ?>
                <th><?= $answer->getText() ?></th>
            <?php endforeach ?>
        </tr>
        </thead>
        <tbody class="jsResults">
            <?php // results are rendered in the PollVotingForm JS component ?>
        </tbody>
    </table>
</div>