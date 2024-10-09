<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Scores</title>
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
            font-size: 2rem;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            max-width: 600px;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        .winner {
            background-color: #ffd700;
            /* Gold for the highest score */
            font-weight: bold;
            font-size: 1.1rem;
        }

        .back-button {
            text-decoration: none;
            color: #ffffff;
            background-color: #007bff;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <h1>Player Scores</h1>
    <table>
        <thead>
            <tr>
                <th>Player Name</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($scores as $score)
                <tr class="{{ $loop->first ? 'winner' : '' }}">
                    <td>{{ $score->player_name }}</td>
                    <td>{{ $score->points }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ url()->previous() }}" class="back-button">Back</a>
</body>

</html>
