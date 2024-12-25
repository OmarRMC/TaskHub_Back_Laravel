@extends('dashboard')
@section('content')

<div style="display: flex; ">
    <a href="{{ route('task.create') }}"
        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-5 py-2.5 me-2 mb-2 m-auto dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 ">
        Add new task
    </a>
</div>
@if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
        <span class="font-medium">{{ session('success') }}</span>
    </div>
@endif
@if(session('completed'))
    <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
        <span class="font-medium">{{ session('completed') }}</span>
    </div>
@endif
<div style="display: flex; gap:10px; flex-direction: column ">
    @forelse ($tasks as $task)
        <div
            class="block px-3 py-2 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
            <h5 class="mb-2 text-sx font-bold tracking-tight text-gray-900 dark:text-white">{{ $task->title }}</h5>
            <p class="font-normal text-xs text-gray-700 dark:text-gray-400">{{ $task->description }}</p>
            <div style="display: flex; justify-content: space-between;  align-items: center">
                @if ($task->completed)
                    <p style="color: green" class="text-xs"> Completed </p>
                @else
                    <p style="color: orange" class="text-xs"> Pending </p>
                @endif
                <div style="display: flex; align-items: center">
                    <a href="{{ route('task.edit', $task) }}" type="button"
                        class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-xs px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Edit</a>
                    <form action="{{ route('task.destroy', $task) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-xs px-5 py-2.5 text-center me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <span>You have no tasks</span>
    @endforelse
</div>
    {{ $tasks->appends(['limit' => $limit])->links() }}
@endsection
