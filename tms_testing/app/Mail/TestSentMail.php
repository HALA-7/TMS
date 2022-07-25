<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestSentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $password='';

    public function __construct($password)
    {
      $this->password=$password;
    }


    public function build()
    {
        return $this->view('email')
            ->subject('get your information to entry to the application')
            ->from('halaali.sy@gmail.com')
            ->with('password',$this->password);

    }
}
