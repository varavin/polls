<?php
/**
 * @var \Polls\Models\Poll $poll
 * @var \Polls\Models\Vote[] $results
 */
?>
<div class="poll">
    <h1>
        <?= $poll->getQuestion() ?>
    </h1>

    <div class="ex2-question" id="pollVotingForm">
        <input type="hidden" id="pollUid" value="<?= $poll->getUid() ?>">
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
            <input id="buttonVote" type="submit" class="btn" value="Submit">
        </div>
        <div><p id="errorMessage" class="errorMessage"></p></div>
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
        <tbody>
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