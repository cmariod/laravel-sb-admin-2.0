<?php

namespace App\Mail;

use File;
use Log;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAsAttachment extends Mailable
{
    use Queueable, SerializesModels;
    
    protected $excelData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($excelData)
    {
        $this->excelData = $excelData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      $this->view('emails.empty')
        ->attach($this->excelData->store("xlsx", false, true)['full']);
    }
}
