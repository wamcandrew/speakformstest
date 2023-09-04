<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Answer;

class AnswerController extends Controller
{
    public function show($id) {
        $survey = Answer::where('survey_completion_id', $id)->get();

        return response()->json($survey, 201);
    }

    public function store(Request $request) {
        $article = Answer::create($request->all());

        return response()->json($article, 201);
    }

    public function upload_audiofile(Request $request) {
        $file = new File($request->file('audio'));
        $aws_bucket = env("AWS_BUCKET", "audiorec-uploads");

        $path = Storage::disk('s3')->putFile(
            $request->id, $file
        );

        $path = 'https://s3.amazonaws.com/'.$aws_bucket.'/'.$path;

        //$path = Storage::append('submit.txt', $request->getContent());

        return response()->json($path, 201);
    }
}
