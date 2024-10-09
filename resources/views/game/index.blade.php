@extends('layouts.layout')

@section('title', 'เล่นเกม OX')

<style>
    body {
        background-color: #f0f8ff;
        font-family: 'Sarabun', sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }

    h1 {
        color: #333;
    }

    h3 {
        color: #555;
    }

    #game-board {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    .cell {
        width: 100px;
        height: 100px;
        border: 2px solid #333;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        background-color: #ffffff;
        transition: background-color 0.3s;
        cursor: pointer;
    }

    .cell:hover {
        background-color: #e0f7fa;

    }

    .cell.X {
        color: #ff4081;

    }

    .cell.O {
        color: #3f51b5;

    }

    button {
        padding: 10px 20px;
        font-size: 16px;
        color: white;
        background-color: #2196f3;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #1976d2;

    }
</style>

@section('content')
    <div class="container">
        <h1>เล่นเกม OX</h1>
        <h3>ชื่อ: {{ $score->player_name }}</h3>
        <h3>คะแนน: {{ $score->points }}</h3>
        <h3>ชนะติดต่อกัน: {{ $score->consecutive_wins }}</h3>
        <div id="game-board" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
            @for ($i = 0; $i < 9; $i++)
                <div class="cell"
                    style="width: 100px; height: 100px; border: 1px solid #000; display: flex; align-items: center; justify-content: center; font-size: 24px;"
                    data-cell="{{ $i }}">

                </div>
            @endfor
        </div>
        <button id="reset-button">เริ่มใหม่</button>
    </div>

    <script>
        const cells = document.querySelectorAll('.cell');
        let currentPlayer = 'X';
        let playerMove;

        cells.forEach(cell => {
            cell.addEventListener('click', () => {
                if (cell.textContent === '') {
                    cell.textContent = currentPlayer;
                    cell.classList.add(currentPlayer);
                    playerMove = cell.getAttribute('data-cell');


                    fetch('/game/play', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                player_move: playerMove
                            })
                        }).then(response => response.json())
                        .then(data => {

                            if (data.winner) {
                                alert(data.winner + ' ชนะ!');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else if (data.winner === 'เสมอ') {
                                alert('เสมอ!');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else if (data.bot_move !== null) {

                                const botCell = document.querySelector(
                                    `.cell[data-cell="${data.bot_move}"]`);
                                botCell.textContent = 'O';
                                botCell.classList.add('O');

                                if (data.winner) {
                                    alert(data.winner + ' ชนะ!');
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                }
                            }
                        });
                }
            });
        });

        document.getElementById('reset-button').addEventListener('click', () => {
            resetGame();
        });

        function resetGame() {
            cells.forEach(cell => cell.textContent = '');
            currentPlayer = 'X';
        }
    </script>
@endsection
