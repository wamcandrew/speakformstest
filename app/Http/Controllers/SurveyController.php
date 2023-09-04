<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyCompleted;
use App\Respondent;
use App\Answer;
use App\Question;

class SurveyController extends Controller
{
    public function show($id) {
        $survey = Survey::where('survey_id', $id)->first();

        return response()->json($survey, 201);
    }

    public function store(Request $request) {
        $survey = Survey::create($request->all());

        return response()->json($survey, 201);
    }

    public function created_on(Request $request) {
        $survey_completion = SurveyCompleted::create($request->all());

        return response()->json($survey_completion, 201);
    }

    public function get_notification_by_id($id, $offset, $limit) {        
        $notification_list = SurveyCompleted::where('created_user_id', $id)->orderBy('completed_on', 'desc')->skip($offset)->take($limit)->get();
        
        return response()->json($notification_list, 201);
    }

    public function get_surveys_by_id($id, $offset, $limit) {
        $notification_list = Survey::where('user_id', $id)->orderBy('created_at', 'DESC')->skip($offset)->take($limit)->get();

        return response()->json($notification_list, 201);
    }

    public function notification_sent($id) {
        $survey_completed = SurveyCompleted::where('survey_completion_id', $id)->update(array('notification_sent' => 1));

        return response()->json($survey_completed, 201);
    }

    public function retrieve_complete_user($id, $offset, $limit){
        $result = array();
        $survey_completions = SurveyCompleted::where('created_user_id', $id)->orderBy('completed_on', 'desc')->skip($offset)->take($limit)->get();

        foreach($survey_completions as $key=>$value){
            $respondent_email = Respondent::where('respondent_id', $value['respondent_id'])->first()->respondent_email;
            $survey_name = Survey::where('survey_id', $value['survey_id'])->first()->survey_name;

            $result[$key] = ["survey_completion_id" => $value['survey_completion_id'], "survey_name" => $survey_name, "respondent_email" => $respondent_email];
        }

        return response()->json($result, 201);
    }

    public function retrieve_complete_id($id){
        $result = array();
        $survey_completions = SurveyCompleted::where('survey_id', $id)->orderBy('completed_on', 'desc')->get();

        foreach($survey_completions as $key=>$value){
            $respondent_email = Respondent::where('respondent_id', $value['respondent_id'])->first()->respondent_email;
            $survey_name = Survey::where('survey_id', $value['survey_id'])->first()->survey_name;

            $result[$key] = ["survey_completion_id" => $value['survey_completion_id'], "survey_name" => $survey_name, "respondent_email" => $respondent_email];
        }

        return response()->json($result, 201);

    }

    public function retrieve_complete($id) {
        $survey_completed = SurveyCompleted::select('completed_on', 'survey_id', 'respondent_id')->where('survey_completion_id', $id)->get();
        $survey_name = Survey::select('survey_name')->where('survey_id', $survey_completed[0]->survey_id)->first();
        $respondent = Respondent::select('respondent_email')->where('respondent_id', $survey_completed[0]->respondent_id)->get();
        $response_data = array();
        $response_data['survey_completed_on'] = $survey_completed[0]->completed_on;
        $response_data['survey_id'] = $survey_completed[0]->survey_id;
        $response_data['survey_respondent'] = $respondent[0]->respondent_email;
        $response_data['survey_name'] = $survey_name->survey_name;

        return response()->json($response_data, 201);
    }

    public function delete_survey($id) {
        $result = Respondent::where('survey_id', $id)->delete();
        $result = Answer::where('survey_id', $id)->delete();
        $result = Question::where('survey_id', $id)->delete();
        $result = SurveyCompleted::where('survey_id', $id)->delete();
        $result = Survey::where('survey_id', $id)->delete();

        return response()->json($result, 201);    
    }
}
