@extends('dashboard')
@section('content')
    <h2>Update the task</h2>
    <hr class="m-4" />
    <form class="max-w-sm mx-auto" method="POST" action="{{ route('task.update', $task) }}">
        @method('PUT')
        @include('task._form', ['task'=>$task, 'editing'=>$editing])
    </form>
@endsection
