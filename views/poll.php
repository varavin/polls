<?php
/**
 * @var \Polls\Models\Poll $poll
 * @var array $results
 */
?>
<div class="poll">
    <h1>
        <?= $poll->getQuestion() ?>
    </h1>

    <div class="ex2-question">
        <div class="ex2-question__label">
            Your name:
        </div>
        <div class="ex2-question__input">
            <input type="text" class="input-text" >
        </div>
        <div class="ex2-question__answer">
            <?php foreach ($poll->getAnswers() as $answer): /** @var \Polls\Models\Answer $answer */ ?>
                <label>
                    <input type="radio" name="do-we-go" value="<?= $answer->getId() ?>">
                    <?= $answer->getText() ?>
                </label>
            <?php endforeach ?>
        </div>
        <div class="ex2-question__submit">
            <input type="submit" class="btn" value="Submit">
        </div>
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
                <td><?= $result['name'] ?></td>
                <?php foreach ($poll->getAnswers() as $answer): ?>
                    <?php if ($answer->getId() === $result['answerId']): ?>
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