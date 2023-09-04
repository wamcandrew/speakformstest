<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SurveyCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $survey_name;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($survey_name, $url)
    {
        $this->survey_name = $survey_name;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('jvk1256@gmail.com')->view('emails.surveyCreated')->with([
            'survey_name' => $this->survey_name,
            'url' => $this->url,
        ]);
    }
}
