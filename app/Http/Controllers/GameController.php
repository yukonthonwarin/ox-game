<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        // ตรวจสอบว่าผู้ใช้ล็อกอินอยู่
        if (Auth::check()) {
            // ดึงผู้ใช้ที่ล็อกอินอยู่
            $user = Auth::user();

            // ดึงหรือสร้างคะแนนของผู้ใช้
            $score = $this->getOrCreateScore($user->id); // เพิ่ม user_id เป็นพารามิเตอร์

            // รีเซ็ตกระดานเกม
            $this->resetBoard();

            return view('game.index', compact('score'));
        }

        // ถ้าผู้ใช้ไม่ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้าล็อกอินหรือหน้าอื่น ๆ
        return redirect()->route('login')->withErrors('กรุณาล็อกอินเพื่อเล่นเกม');
    }


    public function play(Request $request)
    {
        $playerMove = $request->input('player_move');
        $board = $this->updateBoard($playerMove);

        // ใช้ getOrCreateScore เพื่อดึงคะแนน
        $score = $this->getOrCreateScore();

        if ($this->checkWinner($board, 'X')) {
            $this->updateScore('player');
            return response()->json(['winner' => 'ผู้เล่น']);
        }

        // ตรวจสอบว่าเกมเสมอ
        if (count(array_filter($board)) === 9) {
            $this->updateScore('draw'); // เรียกใช้ updateScore เมื่อเสมอ
            return response()->json(['winner' => 'เสมอ']);
        }

        $botMove = $this->botMove($board);
        if ($botMove !== null) {
            $board[$botMove] = 'O';

            if ($this->checkWinner($board, 'O')) {
                $this->updateScore('bot');
                return response()->json(['winner' => 'บอท']);
            }

            session(['board' => $board]);
            return response()->json(['bot_move' => $botMove]);
        }

        return response()->json(['winner' => null]);
    }




    private function updateBoard($playerMove)
    {

        $board = session('board', array_fill(0, 9, null));


        $board[$playerMove] = 'X';


        session(['board' => $board]);

        return $board;
    }

    private function resetBoard()
    {

        session(['board' => array_fill(0, 9, null)]);
    }


    private function botMove(&$board)
    {

        $winningMove = $this->findWinningMove($board, 'O');
        if ($winningMove !== null) {
            return $winningMove;
        }

        // ป้องกันไม่ให้ผู้เล่นชนะในรอบถัดไป
        $blockingMove = $this->findWinningMove($board, 'X');
        if ($blockingMove !== null) {
            return $blockingMove;
        }

        // หาโอกาสว่างที่บอทสามารถเล่นได้
        $availableMoves = array_keys(array_filter($board, function($cell) {
            return $cell === null;
        }));

        if (!empty($availableMoves)) {
            $randomIndex = array_rand($availableMoves);
            return $availableMoves[$randomIndex];
        }
        return null;
    }

    private function findWinningMove($board, $player)
    {

        $winningCombinations = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8],
            [0, 3, 6], [1, 4, 7], [2, 5, 8],
            [0, 4, 8], [2, 4, 6]
        ];

        foreach ($winningCombinations as $combination) {
            if ($board[$combination[0]] === $player && $board[$combination[1]] === $player && $board[$combination[2]] === null) {
                return $combination[2];
            } elseif ($board[$combination[0]] === $player && $board[$combination[2]] === $player && $board[$combination[1]] === null) {
                return $combination[1];
            } elseif ($board[$combination[1]] === $player && $board[$combination[2]] === $player && $board[$combination[0]] === null) {
                return $combination[0];
            }
        }

        return null;
    }


    private function checkWinner($board, $player)
    {

        $winningCombinations = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8],
            [0, 3, 6], [1, 4, 7], [2, 5, 8],
            [0, 4, 8], [2, 4, 6]
        ];

        foreach ($winningCombinations as $combination) {
            if ($board[$combination[0]] === $player &&
                $board[$combination[1]] === $player &&
                $board[$combination[2]] === $player) {
                return true;
            }
        }
        return false;
    }


    private function getOrCreateScore()
    {
        // ตรวจสอบว่าผู้ใช้ล็อกอินอยู่
        $userId = auth()->user() ? auth()->user()->id : null;

        // หากผู้ใช้ล็อกอิน ให้ดึงคะแนนของผู้ใช้
        $score = Score::where('user_id', $userId)->first();

        // ถ้ายังไม่มีคะแนนสำหรับผู้ใช้ ให้สร้างใหม่
        if (!$score) {
            $playerName = auth()->user() ? auth()->user()->name : 'ผู้เล่น';

            $score = Score::create([
                'user_id' => $userId,
                'player_name' => $playerName,
                'points' => 0,
                'consecutive_wins' => 0,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }

        return $score;
    }


    private function updateScore($winner)
    {
        $score = $this->getOrCreateScore();

        if ($winner === 'player') {
            $score->points += 1; // เพิ่มคะแนนเมื่อผู้เล่นชนะ
            $score->consecutive_wins += 1; // เพิ่มการชนะติดต่อกัน

            // เช็คว่า ชนะติดต่อกัน 3 ครั้งหรือไม่
            if ($score->consecutive_wins === 3) {
                $score->points += 1; // เพิ่มคะแนนพิเศษ
                $score->consecutive_wins = 0; // รีเซ็ตการชนะติดต่อกัน
            }
        } elseif ($winner === 'bot') {
            $score->points -= 1; // ลดคะแนนเมื่อแพ้
            $score->consecutive_wins = 0; // รีเซ็ตการชนะติดต่อกัน
        } elseif ($winner === 'draw') {
            $score->consecutive_wins = 0; // รีเซ็ตการชนะติดต่อกันเมื่อเสมอ
        }

        $score->save();
    }



    public function resetGame()
    {
        $score = $this->getOrCreateScore();
        $score->points = 0;
        $score->consecutive_wins = 0;
        $score->save();

        $this->resetBoard();
    }





    private function playGame($playerMove)
    {
        // Logic สำหรับการเล่นเกมและตรวจสอบผู้ชนะ (รวมถึงบอท)
        // คืนค่าผลลัพธ์ว่าใครเป็นผู้ชนะ
    }
}
