<?php

namespace StrSocial\Bundle\SmsQueueBundle\Component;
use Vresh\TwilioBundle\Twilio\Twilio;

use StrSocial\Bundle\SmsQueueBundle\Entity\BufferMessage;
use Doctrine\ORM\EntityManager;

class Sender
{

    private $em;
    private $twilio;
    private $sending_interval;
    private $buffer_enable;

    const CONF_FLUSH_BUFFER_EVERY_N_SENT = 10;

    public function __construct ( 
            EntityManager $entityManager,
            Twilio $twilio,
            $sending_interval,
            $buffer_enable = FALSE )
    {
        $this->em = $entityManager;
        $this->twilio = $twilio;
        $this->sending_interval = $sending_interval;
        $this->buffer_enable = (bool) $buffer_enable;

    }

    public function send ( MessageInterface $message )
    {
        $phone = $message->getPhoneNumber ( );
        $text = $message->getTextMessage ( );

        $this->sendOrAddToBuffer ( $phone, $text );
    }

    protected function sendOrAddToBuffer ( $phone, $text )
    {
        if ( $this->buffer_enable )
        {
            $this->addToBuffer ( $phone, $text );
        }
        else
        {
            $this->deliver ( $phone, $text );
        }
    }

    protected function addToBuffer ( $phone, $text )
    {
        $buffer = new BufferMessage ( );
        $buffer->setPhoneNumber ( $phone );
        $buffer->setText ( $text );

        $this->em
                ->persist ( $buffer );
        $this->em
                ->flush ( );
    }

    protected function deliverBuffer ( )
    {
        $all = $this->em
                    ->getRepository ( 'StrSocialSmsQueueBundle:BufferMessage' )
                    ->findAll ( );

        $count = 0;
        foreach ( $all as $bmsg )
        {

            try
            {
                $this->deliver ( $bmsg->getPhoneNumber ( ), $bmsg->getText ( ) );
                $this->em
                        ->remove ( $bmsg );
            }
            catch ( Exception $e )
            {

            }
            $count++;
            if ( $count > self::CONF_FLUSH_BUFFER_EVERY_N_SENT )
            {
                $this->em
                        ->flush ( );
                $count = 0;
            }
        }
        $this->em
                ->flush ( );
    }

    protected function deliver ( $phone, $text )
    {
        $this->twilio
                ->account
                ->sms_messages
                ->create ( '13852444845', $phone, $text );
    }
}
