<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
<h1>Forgot Your Password?</h1>

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
    </div>

    <div>
        <button type="submit">Send Password Reset Link</button>
    </div>
</form>
</body>
</html>
