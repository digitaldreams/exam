<table class="table table-bordered table-striped">
    <thead>
    <tr>
    		<th>Exam Id </th>
		<th>Email </th>
		<th>Status </th>
		<th>Token </th>
		<th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
    <tr>	 	<td> {{$record->exam_id }} </td>
	 	<td> {{$record->email }} </td>
	 	<td> {{$record->status }} </td>
	 	<td> {{$record->token }} </td>
	<td><a href="{{route('exam::exam_invitations.show',$record->id)}}">
    <span class="fa fa-eye"></span>
</a><a href="{{route('exam::exam_invitations.edit',$record->id)}}">
    <span class="fa fa-pencil"></span>
</a>
<form onsubmit="return confirm('Are you sure you want to delete?')"
      action="{{route('exam::exam_invitations.destroy',$record->id)}}"
      method="post"
      style="display: inline">
    {{csrf_field()}}
    {{method_field('DELETE')}}
    <button type="submit" class="btn btn-default cursor-pointer  btn-sm">
        <i class="text-danger fa fa-remove"></i>
    </button>
</form></td></tr>

    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="3">
            {{{$records->render()}}}
        </td>
    </tr>
    </tfoot>
</table>