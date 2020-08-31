<script type="text/javascript">

    function addOption() {
        var trLast = $('#tblOptions').find('tr:last');
        var lastIndex = trLast.find("input[name=optionNumber]").val();

        var trNew = trLast.clone(true, true);
        var nextIndex = parseInt(lastIndex) + parseInt(1);

        trNew.find(".isCorrect").attr('name', "options[isCorrect][" + nextIndex + "]]");
        trNew.find(".option").val('').attr('name', "options[option][" + nextIndex + "]]");
        trNew.find("input[name='optionNumber']").val(nextIndex);
        trLast.after(trNew);

        return false;
    }

    function addOptionForFillInTheBlank(index) {
        var trLast = $('#fillInTheBlankAnswerTable').find('tr:last');
        var trNew = trLast.clone(true, true);

        trNew.attr('id', index);
        trNew.find(".questionKey").attr('name', "answers[" + index + "][key]").val("" + index + "");
        trNew.find(".option").val('').attr('name', "answers[" + index + "][value]");
        trLast.before(trNew);
        return false;
    }

    function removeOption(e) {
        if (e.parent().parent()) {
            e.parent().parent().remove();
        }
        return false;
    }

    @if(request('answer_type')==\Exam\Enums\QuestionAnswerType::FILL_IN_THE_BLANK)
    $("#fill_in_the_blank_summary").summernote({
        width: '100%',
        height: '400px',
        callbacks: {
            onChange: function (contents, $editable) {
                findQuestionNumber(contents)
            }
        }
    });

    function findQuestionNumber(contents) {
        var res = contents.match(/\((.*?)\)/g);
        var availableIds = [];
        $(".answerTr").each(function (v) {
            var id = $(this).attr('id');
            if (id.length > 0) {
                availableIds.push(id);
            }
        });

        if (Array.isArray(res)) {
            res.forEach(function (v) {
                if (availableIds.includes(v) === false) {
                    addOptionForFillInTheBlank(v);
                }
            });
        }

    }

    @endif

</script>
