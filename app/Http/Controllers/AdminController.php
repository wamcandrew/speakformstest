<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Survey;
use App\SurveyCompleted;
use App\Question;
use App\Answer;
use App\Respondent;

class AdminController extends Controller
{
    public function get_user_details($email){
        $user_details = User::where('email', $email)->first();

        return response()->json($user_details, 201);
    }

    public function delete_user($id){
        try {
            $query = $this->clean_user_surveys($id);
            $result = User::where('id', $id)->delete();
            return response()->json($query, 201);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function clean_user_data($id){
        try {
            $query = $this->clean_user_surveys($id);
            return response()->json($query, 201);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    function clean_user_surveys($id){
        $surveys = Survey::where('user_id', $id)->get();
        
        foreach($surveys as $survey){
            $survey_id = $survey['survey_id'];
            Respondent::where('survey_id', $survey_id)->delete();
            Answer::where('survey_id', $survey_id)->delete();
            Question::where('survey_id', $survey_id)->delete();
            SurveyCompleted::where('survey_id', $survey_id)->delete();
            Survey::where('survey_id', $survey_id)->delete();
        }
    }
}
