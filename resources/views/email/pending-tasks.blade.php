<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Task Reminder</title>
</head>

<body>
    <h1>Hi, {{ $user->name }} 👋</h1>

    <p>You have pending tasks to complete. Here is the list of tasks:</p>

    <ol>
        @foreach ($user->incompleteTasks as $task)
            <li>
                <h3>{{ $task->title }}</h3>
                <p>{{ $task->description }}</p>
            </li>
        @endforeach
    </ol>
    <p>Don't leave for tomorrow what you can do today! 😄</p>

</body>

</html>
