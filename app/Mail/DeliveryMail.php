<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeliveryMail extends Mailable{
    use Queueable, SerializesModels;
    public $data;
    public function __construct($data) {
        $this->data = $data;
    }
    public function build(){
        $address = 'info@saimongroup.com.bd';
        $subject = 'Visa Information';
        $name    = 'Visa Saimon';
        return $this->view('emails.delivery')
            ->from($address, $name)
            ->cc($address, $name)
            ->bcc($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with([
                'passport'=>$this->data['passport'],
                'datas'=>$this->data['datas']
            ]);
    }
}
