<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Respondent;

class RespondentController extends Controller
{
    public function show($id) {
        $survey = Respondent::where('respondent_id', $id)->first();

        return response()->json($survey, 201);
    }

    public function store(Request $request) {
        $article = Respondent::create($request->all());

        return response()->json($article, 201);
    }
}
