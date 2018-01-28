<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 24.01.18
 * Time: 18:47
 */

namespace App\Additional;


use Illuminate\Http\Request;
use Swift_Mailer;
use Swift_SmtpTransport;
use Illuminate\Support\Facades\Mail;

class SendEmail
{
    private $array = [
        'info@hotel-service.co.il'=>[
            'password'=>'wdaV@^3*Ydin',
            'name'=>'info@hotel-service.co.il',
            'smtp'=>'scp68.hosting.reg.ru',
            'port'=>465,
            'type'=>'ssl'
        ]
    ];

    private $sample = ['email_from'=>'info@hotel-service.co.il','message'=>
        [
            'email'=>'jeniabuianov@gmail.com',
            'subject'=>'Ежедневная рассылка',
            'email_from'=>'info@hotel-service.co.il',
            'company'=>'Hotel Service',
            'text'=>'Hello'
        ]
    ];

    public function __construct($array = []){

        if (empty($array)) $array = $this->sample;

        $backup = Mail::getSwiftMailer();

        $email = $this->array[$array['email_from']];
        $transport = new Swift_SmtpTransport($email['smtp'],$email['port'],$email['type']);
        $transport->setUsername($email['name']);
        $transport->setPassword($email['password']);
        $customEmail = new Swift_Mailer($transport);


        Mail::setSwiftMailer($customEmail);

        $send = $array['message'];
        Mail::send('email', $send, function($message) use ($send) {
            $message->to($send['email']);
            $message->subject($send['subject']);
            $message->from($send['email_from'], $send['company']);
            if (!empty($send['file']))
                $message->attach($send['file']);
        });

        Mail::setSwiftMailer($backup);
    }
}