<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomercredentialMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function build()
    {
        // $address = 'visa.support@saimonholidays.com';
        $address = 'info@saimongroup.com.bd';
        $subject = 'User Credentials';
        $name    = 'Visa Saimon';
        return $this->view('emails.customer-credentials')
            ->from($address, $name)
            ->cc($address, $name)
            ->bcc($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with([
                'email' => $this->data['email'],
                'password' => $this->data['password'],
                'name'     => $this->data['name'],
            ]);
    }
}
