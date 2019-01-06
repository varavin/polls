<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <script src="/js/functions.js"></script>
    <script src="/js/app.js"></script>
    <link href="/css/style.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto+Condensed:300,400,400i,700" media="all">
    <title>XIAG test task</title>
    <meta name="robots" content="noindex,nofollow" />
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0" />
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
</head>
<body>
<div class="page">
    <div class="page__header">
        <div class="page__logo">
            <a href="https://www.xiag.ch" target="_blank">
                <img src="/images/page-logo.png" alt="XIAG AG">
            </a>
        </div>
        <div class="page__task-name">
            Poll website task: creating new poll
        </div>
    </div>
    <div class="page__image">
        <div class="page__task-title">
            Poll website task: creating new poll
        </div>
    </div>
    <div class="page__content page__content--padding">
        <div class="poll">
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
    </div>
</div>
</body>
</html>