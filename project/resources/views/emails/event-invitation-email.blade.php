<div style="width: 70%; border: 1px solid black; border-radius: 2px; padding:0.5em;">
    <h1 style="margin-bottom: 0;"> You are invited to <a href="{{ $eventLink }}"> {{ $event->name }} </a></h1>

    <div style="white-space: pre-wrap; margin: 0.35em 1em; text-align: justify;">
        {{$event->description}}
    </div>

    <div style="text-align: center">
        <a href="{{$acceptInvitationLink}}">
            <div style="color: green; font-weight: 600; display: inline-block; line-height: 2em; border: 2px solid green; border-radius: 7px;
                padding:0.25em;">
                Accept
            </div>
        </a>
    </div>
</div>