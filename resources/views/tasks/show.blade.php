<h2>Σχόλια</h2>

<form method="POST" action="{{ route('tasks.comments.store', $task) }}">
    @csrf
    <textarea name="content"></textarea>
    <button type="submit">Προσθήκη</button>
</form>

<hr>

@foreach ($task->comments as $comment)
    <div style="margin-bottom:10px;">
        <strong>{{ $comment->user->name }}</strong><br>
        {{ $comment->content }}
    </div>
@endforeach