Hola Daniel,
<p>
    estos son los {{$data->subject}} del dia.
</p>
<br/>
@foreach($data->users as $user)
    <p>
    "{{$user}}",
    </p>
    @endforeach
<br/>
<p>
buen dia
</p>

