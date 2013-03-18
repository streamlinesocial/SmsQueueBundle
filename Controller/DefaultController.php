<?php

namespace StrSocial\Bundle\SmsQueueBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use StrSocial\Bundle\SmsQueueBundle\Component\Message;

class DefaultController extends Controller
{

  /**
   * example of usage
   */
    public function indexAction ( $phone, $text )
    {
       // example of usage
        $control = $this->get ( 'str_social_sms_queue.control' );
        $message = new Message ( );
        $message->setPhoneNumber ( $phone );
        $message->setTextMessage ( $text );
        $extra = serialize ( $message );

        $control->send ( $message );

        return $this->render ( 
                        'StrSocialSmsQueueBundle:Default:index.html.twig',
                        array(
                                'text' => $text, 'phone' => $phone,
                                'extra' => $extra
                        ) );
    }
}
