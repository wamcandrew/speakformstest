<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;

class QuestionController extends Controller
{
    public function show($id) {
        $question = Question::where('survey_id', $id)->get();

        return response()->json($question, 201);
    }

    public function store(Request $request) {
        $question = Question::create($request->all());

        return response()->json($question, 201);
    }
}
