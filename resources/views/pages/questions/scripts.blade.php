<script type="text/javascript">

    function addOption() {
        var trLast = $('#tblOptions').find('tr:last');
        var lastIndex = trLast.find("input[name=optionNumber]").val();
        console.log(lastIndex);

        var trNew = trLast.clone(true, true);
        var nextIndex = parseInt(lastIndex) + parseInt(1);

        trNew.find(".isCorrect").attr('name', "options[isCorrect][" +  nextIndex + "]]");
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

</script>
