<?php
/**
 * @var \Polls\App $this
 * @var \Polls\Models\Poll $poll
 * @var \Polls\Models\Vote[] $results
 */

$this->addJsComponent('PollVotingForm');

?>
<div class="poll">
    <h1>
        <?= $poll->getQuestion() ?>
    </h1>

    <div class="jsComponentPollVotingForm ex2-question">
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
        <?php foreach ($results as $result): ?>
            <tr>
                <td><?= $result->getVisitorName() ?></td>
                <?php foreach ($poll->getAnswers() as $answer): ?>
                    <?php if ($answer->getId() === $result->getAnswerId()): ?>
                        <td>x</td>
                    <?php else: ?>
                        <td></td>
                    <?php endif ?>
                <?php endforeach ?>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>