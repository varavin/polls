<?php
/**
 * @var \Polls\App $this
*/

$this->addJsComponent('PollCreationForm');

?>
<div class="poll jsComponentPollCreationForm">
    <table class="poll-table">
        <thead>
        <tr>
            <th>Question:</th>
            <th>
                <input type="text" value="Where do we go out tonight?" class="jsQuestionInput input-text" />
            </th>
        </tr>
        </thead>
        <tbody>
        <tr class="jsAnswerRow">
            <th>Answer 1:</th>
            <td><input type="text" value="Yes" class="jsAnswerInput input-text" /></td>
        </tr>
        <tr class="jsAnswerRow">
            <th>Answer 2:</th>
            <td><input type="text" value="No" class="jsAnswerInput input-text" /></td>
        </tr>
        <tr>
            <td class="poll-table__plus">
                <button class="jsAddAnswer btn btn--plus">
                    +
                </button>
            </td>
            <td> </td>
        </tr>
        </tbody>
    </table>

    <button class="jsStartPoll btn btn--start">
        Start
    </button>
    <div><p class="jsErrorMessage errorMessage"></p></div>
</div>