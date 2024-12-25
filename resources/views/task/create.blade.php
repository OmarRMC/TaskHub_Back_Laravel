@extends('dashboard')
@section('content')
    <h2>Create New Task</h2>
    <hr class="m-4" />
    <form class="max-w-sm mx-auto" method="POST" action="{{ route('task.store') }}">
        @include('task._form')
    </form>
@endsection
