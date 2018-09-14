<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class SendMail extends Mailable {

    use Queueable,
        SerializesModels;

    public $data;

    public function __construct($data) {        
  
        $this->subject  = $data['subject'];
        $this->status   = $data['data']['status'];
        $this->data     = $data['data'];
    }
 
    public function build() {
        if ($this->status == 'register') {
            return $this->subject($this->subject)->view('emails.register');
        }
        if ($this->status == 'order') {
            return $this->subject($this->subject)->view('emails.sendmail');
        }
    }

}
