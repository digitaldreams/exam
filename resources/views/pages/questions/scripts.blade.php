<script type="text/javascript">

    function addOption() {
        var trLast = $('#tblOptions').find('tr:last');
        var lastIndex = trLast.find("input[name=optionNumber]").val();
        console.log(lastIndex);

        var trNew = trLast.clone(true, true);
        var nextIndex = parseInt(lastIndex) + parseInt(1);

        trNew.find(".isCorrect").attr('name', "options[isCorrect][" + nextIndex + "]]");
        trNew.find(".option").val('').attr('name', "options[option][" + nextIndex + "]]");
        trNew.find("input[name='optionNumber']").val(nextIndex);
        trLast.after(trNew);

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
        placeholder: 'e.g. Once upon a time there was a (1)..... She was 12 years (2).....',
        width: '100%',
        height: '400px',
        callbacks: {
            onChange: function (contents, $editable) {
                findQuestionNumber(contents)
            }
        }
    });
        function findQuestionNumber(contents){
            var res = contents.match(/\((.*?)\)/g);
            console.log(res);
        }
//
    @endif

</script>
