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

### Configuration Example

  twilio.api.class: Vresh\TwilioBundle\Twilio\Twilio
  str_social_sms_queue.twilio.from: "%twilio.phone_number%"
  str_social_sms_queue.twilio.parameters:
      # you must replate following values with proper ones from twilio
      sid: "%twilio.sid%"
      authToken: "%twilio.authToken%"
      
  # if 'off' or anything that php will consider empty then the queue 
  # stop/start will not happen.
  str_social_sms_queue.queue.start: 22:00:00
  str_social_sms_queue.queue.stop: 08:00:00
