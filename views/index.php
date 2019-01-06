<div class="poll" id="pollCreationForm">
    <table class="poll-table">
        <thead>
        <tr>
            <th>Question:</th>
            <th>
                <input id="questionInput" type="text" value="Where do we go out tonight?" class="input-text" />
            </th>
        </tr>
        </thead>
        <tbody>
        <tr class="jsAnswerRow">
            <th>Answer 1:</th>
            <td><input type="text" value="Yes" class="input-text jsAnswerInput" /></td>
        </tr>
        <tr class="jsAnswerRow">
            <th>Answer 2:</th>
            <td><input type="text" value="No" class="input-text jsAnswerInput" /></td>
        </tr>
        <tr>
            <td class="poll-table__plus">
                <button class="btn btn--plus" id="addAnswer">
                    +
                </button>
            </td>
            <td> </td>
        </tr>
        </tbody>
    </table>

    <button class="btn btn--start" id="startPoll">
        Start
    </button>
    <div><p id="errorMessage" class="errorMessage"></p></div>
</div>