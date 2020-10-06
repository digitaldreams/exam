<script type="text/javascript">

    $('#tags').select2({
        tags: true,
        tokenSeparators: [",",],
        createSearchChoice: function (term, data) {
            if ($(data).filter(function () {
                return this.text.localeCompare(term) === 0;
            }).length === 0) {
                return {
                    id: term,
                    text: term
                };
            }
        },
        ajax: {
            url: '{{route('blog::tags.select2')}}',
            dataType: 'json'
        }
    });

    $('#category_id').select2({
        ajax: {
            url: '{{route('blog::categories.select2')}}',
            dataType: 'json'
        }
    });

    $('#must_completed').select2({
        ajax: {
            url: '{{route('exam::exams.select2')}}',
            dataType: 'json'
        }
    });
</script>
