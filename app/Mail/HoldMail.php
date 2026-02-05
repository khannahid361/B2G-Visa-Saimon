<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class HoldMail extends Mailable{
    use Queueable, SerializesModels;
    public $data;
    public function __construct($data) {
        $this->data = $data;
    }
    public function build(){
        // $address = 'visa.support@saimonholidays.com';
        $address = 'info@saimongroup.com.bd';
        $subject = 'Visa Information';
        $name    = 'Visa Saimon';
        return $this->view('emails.hold')
            ->from($address, $name)
            ->cc($address, $name)
            ->bcc($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with([
                'c_name'=>$this->data['c_name'],
                'app_id'=>$this->data['app_id'],
            ]);
    }
}
