<?php

namespace StrSocial\Bundle\SmsQueueBundle\Component;
use Monolog\Logger;

use Vresh\TwilioBundle\Twilio\Twilio;

use StrSocial\Bundle\SmsQueueBundle\Entity\BufferMessage;
use Doctrine\ORM\EntityManager;

class Sender
{

    private $em;
    private $twilio;
    private $sending_interval;
    private $buffer_enable;
    private $from_phone;

    const CONF_FLUSH_BUFFER_EVERY_N_SENT = 10;

    public function __construct ( 
            EntityManager $entityManager,
            Twilio $twilio,
            $sending_interval,
            $from_phone,
            $buffer_enable = FALSE
            )
    {
        $this->em = $entityManager;
        $this->twilio = $twilio;
        $this->sending_interval = $sending_interval;
        $this->buffer_enable = (bool) $buffer_enable;
        $this->from_phone = $from_phone;
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
            $this->addToBuffer ( $phone, $text, TRUE);
            $this->deliver ( $phone, $text );
        }
    }

    protected function addToBuffer ( $phone, $text, $sent = FALSE )
    {
        $buffer = new BufferMessage ( );
        $buffer->setPhoneNumber ( $phone );
        $buffer->setText ( $text );
        $buffer->setSent ( $sent );

        $this->em
                ->persist ( $buffer );
        $this->em
                ->flush ( );
    }

    /**
     * Load all the buffer, and deliver the message not
     * yet sent
     */
    public function deliverBuffer ( )
    {
        $qb = $this->em
                    ->getRepository ( 'StrSocialSmsQueueBundle:BufferMessage' )
                    ->createQueryBuilder('s');
        
        $all = $qb->where('s.sent = false')
                    ->andWhere($qb->expr()->lte('s.count',25))
                    ->getQuery()
                    ->getResult();

        $count = 0;
        $total = 0;
        foreach ( $all as $bmsg )
        {
            try
            {
                $this->deliver ( $bmsg->getPhoneNumber ( ), $bmsg->getText ( ) );
                $bmsg->setSent(true);
                $total++;
            }
            catch ( \Exception $e )
            {
                $bmsg->setSent(false);
            }
            $bmsg->increaseCount();
            
            $count++;
            if ( $count > self::CONF_FLUSH_BUFFER_EVERY_N_SENT )
            {
                $this->em
                        ->flush ( );
                $count = 0;
            }
        }
        $this->em->flush ( );
        
        return $total;
    }

    /**
     * Call 3rd service that will handle the delivery of the message.
     * In this case twilio.
     *  
     * @param unknown_type $phone
     * @param unknown_type $text
     * 
     * @return boolean
     */
    protected function deliver ( $phone, $text )
    {
            $this->twilio
                ->account
                ->sms_messages
                ->create ( $this->from_phone, $phone, $text );
    }
}
