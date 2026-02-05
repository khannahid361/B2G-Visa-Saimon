<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Accept extends Mailable{
    use Queueable, SerializesModels;
    public $data;
    public function __construct($data) {
        $this->data = $data;
    }
    public function build(){
        $address = 'info@saimongroup.com.bd';
        $subject = 'Visa Information';
        $name    = 'Visa Saimon';
        return $this->view('emails.accept')
            ->from($address, $name)
            ->cc($address, $name)
            ->bcc($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with([
                'message' => $this->data['message'] ,
                'user_data' => $this->data['user_data'] ,
            ]);
    }
}
