//= import/codemirror/codemirror.js
//= import/codemirror/foldcode.js
//= import/codemirror/foldgutter.js
//= import/codemirror/brace-fold.js
//= import/codemirror/closetag.js
//= import/codemirror/xml-fold.js
//= import/codemirror/matchtags.js
//= import/codemirror/indent-fold.js
//= import/codemirror/markdown-fold.js
//= import/codemirror/comment-fold.js
//= import/codemirror/closebrackets.js
//= import/codemirror/javascript.js
//= import/codemirror/active-line.js
//= import/codemirror/matchbrackets.js
//= import/codemirror/htmlmixed.js
//= import/codemirror/xml.js
//= import/codemirror/fullscreen.js
//= import/codemirror/css.js
//= import/codemirror/clike.js
//= import/codemirror/show-hint.js
//= import/codemirror/javascript-hint.js
//= import/codemirror/markdown.js
//= import/codemirror/anyword-hint.js
//= import/codemirror/php.js
//= import/codemirror/annotatescrollbar.js
//= import/codemirror/matchesonscrollbar.js
//= import/codemirror/searchcursor.js
//= import/codemirror/match-highlighter.js
//= import/codemirror/dialog.js
//= import/codemirror/search.js
//= import/codemirror/jump-to-line.js
//= import/codemirror/comment.js
//= import/codemirror/hardwrap.js
//= import/codemirror/sublime.js


$(document).on('keydown', function (e) {
    if (e.code == 'KeyS' && e.ctrlKey == true && $('#edit-code-form:visible').length) {
        e.preventDefault();
        $('#edit-code-form:visible').trigger('submit');
    }
});
