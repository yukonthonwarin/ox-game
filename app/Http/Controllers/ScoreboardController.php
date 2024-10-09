<?php

namespace App\Http\Controllers;

use App\Models\Score; // เพิ่มการใช้งานโมเดล Score
use Illuminate\Http\Request;

class ScoreboardController extends Controller
{
    public function index()
    {
        $scores = Score::all();

        return view('scoreboard.index', compact('scores'));
    }
}
