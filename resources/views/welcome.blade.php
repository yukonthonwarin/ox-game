<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Tic-Tac-Toe Game</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #007bff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }
        p {
            font-size: 1.2rem;
            margin: 10px 0;
        }
        a {
            text-decoration: none;
            color: #ffffff;
            background-color: #007bff;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            display: inline-flex;
            align-items: center;
        }
        a:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .welcome-container {
            text-align: center;
            padding: 40px;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .google-icon {
            margin-right: 8px;
            width: 20px;
            height: 20px;
            fill: #ffffff;
        }
        .scoreboard-link {
            display: block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1>Welcome <br> OX Tic-Tac-Toe Game</h1>

        @if(Auth::check())
            <p>Welcome, {{ Auth::user()->name }}!</p>
            <a href="{{ route('game.index') }}">Play Game</a>
        @else
        <p>Please</p>
            <p><a href="{{ url('auth/google') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="google-icon" viewBox="0 0 16 16">
                    <path d="M15.545 6.558a9.4 9.4 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.7 7.7 0 0 1 5.352 2.082l-2.284 2.284A4.35 4.35 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.8 4.8 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.7 3.7 0 0 0 1.599-2.431H8v-3.08z"/>
                </svg>
                login with Google</a>.
            </p>
            <a href="{{ route('scoreboard.index') }}" class="scoreboard-link">View Player Scores</a>
        @endif
    </div>
</body>
</html>
