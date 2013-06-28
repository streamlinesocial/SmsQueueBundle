SmsQueueBundle
==============

A symfony 2.1 bundle that sends SMS messages at appropiate times.


Usage:

// create a maessage
        $message = new Message ( );
        $message->setPhoneNumber ( $phone );
        $message->setTextMessage ( $text );
        $extra = serialize ( $message );

// give it to control
        $control = $this->get ( 'str_social_sms_queue.control' );
        $control->send ( $message );

    