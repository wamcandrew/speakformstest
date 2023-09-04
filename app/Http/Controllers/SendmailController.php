<?php

namespace App\Http\Controllers;
use Log;
use Exception;
use Response;
use Aws\Sns\Exception\InvalidSnsMessageException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Exception\GuzzleException;
use App\Mail\SurveyCreated;
use App\Mail\SurveyCompleted;

use App\WrongEmail;

class SendmailController extends Controller
{
    public function surveyCreated(Request $request)
    {
        //TODO: Test out new PHP Mailer integration SMTP Settings
        $invalid_email = WrongEmail::whereEmail($request->email)->get()->count();
        if($invalid_email == 0) {
            try {
                \Mail::to($request->email)->send(new SurveyCreated($request->survey_name, $request->url));
            } catch(Exception $e) {
                Log::error('Email Exception: '.$e);
                return Response::json(['message'=>'Failed sending the email', 'exception'=>$e], 422);
            }
        } else {
            return Response::json(['message'=>'Invalid Email'], 422);
        }
    }
    
    public function surveyCompleted(Request $request)
    {
        $invalid_email = WrongEmail::whereEmail($request->to_email)->get()->count();
        if($invalid_email == 0) {
            try {
                \Mail::to($request->to_email)->send(new SurveyCompleted($request->survey_name, $request->res_mail, $request->url));
            } catch(Exception $e) {
                Log::error('Email Exception: '.$e);
                return Response::json(['message'=>'Failed sending the email', 'exception'=>$e], 422);
            }
        } else {
            return Response::json(['message'=>'Invalid Email'], 422);
        }
    }

    public function handleBounceOrComplaint(Request $request)
    {
        Log::info($request->json()->all());
        $data = $request->json()->all();
        if($request->json('Type') == 'SubscriptionConfirmation')
        Log::info("SubscriptionConfirmation came at: ".$data['Timestamp']);
        if($request->json('Type') == 'Notification'){
            $message = $request->json('Message');
            $validator = new MessageValidator();
            try {
                if($validator->validate($message)) {
                    switch($message['notificationType']){
                        case 'Bounce':
                        $bounce = $message['bounce'];
                        foreach ($bounce['bouncedRecipients'] as $bouncedRecipient){
                            $emailAddress = $bouncedRecipient['emailAddress'];
                            $emailRecord = WrongEmail::firstOrCreate(['email' => $emailAddress, 'problem_type' => 'Bounce']);
                            if($emailRecord){
                            $emailRecord->increment('repeated_attempts',1);
                            }
                        }
                        break;
                        case 'Complaint':
                        $complaint = $message['complaint'];
                        foreach($complaint['complainedRecipients'] as $complainedRecipient){
                            $emailAddress = $complainedRecipient['emailAddress'];
                            $emailRecord = WrongEmail::firstOrCreate(['email' => $emailAddress, 'problem_type' => 'Complaint']);
                            if($emailRecord){
                            $emailRecord->increment('repeated_attempts',1);
                            }
                        }
                        break;
                        default:
                        // Do Nothing
                        break;
                    }
                }
            } catch (InvalidSnsMessageException $e) {
              Log::error('SNS Message Validation Error: ' . $e->getMessage()); 
            }
        }

        return Response::json(['status' => 200, "message" => 'success']);
    }
}