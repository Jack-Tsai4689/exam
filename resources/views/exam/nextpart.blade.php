<form name="form1" id="form1" method="post" action="{{ url('/exam/start')}}">
	<input type="hidden" name="type" value="{{ $type }}">
	<input type="hidden" name="exnum" value="{{ $exnum }}">
	<input type="hidden" name="exam" value="{{ $exam }}">
	<input type="hidden" name="gra" value="{{ $gra }}">
	<input type="hidden" name="subj" value="{{ $subj }}">
	<input type="hidden" name="chap" value="{{ $chap }}">
	<input type="hidden" name="degree" value="{{ $degree }}">
	<input type="hidden" name="sets" value="{{ $sets }}">
	<input type="hidden" name="lime" value="{{ $lime }}">
	<input type="hidden" name="epart" value="{{ $epart }}">
	<input type="hidden" name="spart" value="{{ $spart }}">
	{{ csrf_field() }}
</FORM>
<script>form1.submit()</script>