<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
<div>
    <h1>MQTT Dashboard</h1>

    <!-- Form to send a message -->
    <form action="{{ route('send.message') }}" method="POST">
        @csrf
        <label for="message">Message:</label>
        <input type="text" id="message" name="message" required>
        <button type="submit">Send Message</button>
    </form>

    <!-- Success message -->
    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <h2>Received Messages</h2>
    <ul>
        @forelse ($messages as $msg)
            <li>{{ $msg }}</li>
        @empty
            <li>No messages received yet.</li>
        @endforelse
    </ul>

    <!-- Authenticated User Area -->
    @auth
        <div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    @else
        <div>
            <a href="{{ route('login') }}">Login</a> |
            <a href="{{ route('register') }}">Register</a> |
            <a href="{{ route('password.request') }}">Forgot Password?</a>
        </div>
    @endauth

</div>
</body>
</html>
