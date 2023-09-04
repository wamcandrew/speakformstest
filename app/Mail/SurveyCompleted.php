<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SurveyCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $survey_name;
    public $res_mail;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($survey_name, $res_mail, $url)
    {
        $this->survey_name = $survey_name;
        $this->res_mail = $res_mail;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('jvk1256@gmail.com')->view('emails.surveyCompleted')->with([
            'survey_name' => $this->survey_name,
            'res_mail' => $this->res_mail,
            'url' => $this->url,
        ]);
    }
}
