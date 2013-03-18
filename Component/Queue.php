<?php

namespace StrSocial\Bundle\SmsQueueBundle\Component;
use StrSocial\Bundle\SmsQueueBundle\Component\MessageInterface;
use StrSocial\Bundle\SmsQueueBundle\Entity\QueuedMessage;
use Doctrine\ORM\EntityManager;

class Queue
{

    public function __construct ( EntityManager $entityManager )
    {
        $this->em = $entityManager;
    }

    public function enqueue ( MessageInterface $message )
    {
        $customType = $message->getMessageTypeKey ( );
        $customValue = $message->getMessageTypeValue ( );
        $timeZone = $message->getTimeZone ( );
        $phoneNumber = $message->getPhoneNumber ( );

        $this->persist ( $phoneNumber, $timeZone, $customType, $customValue,
                        $message );
    }

    protected function persist ( 
            $phoneNumber,
            $timezone,
            $customType,
            $customValue,
            $message )
    {

        $queued = new QueuedMessage ( );
        $queued->setPhoneNumber ( $phoneNumber );
        $queued->setCustomType ( $customType );
        $queued->setCustomValue ( $customValue );
        $queued->setTimezone ( $timezone );
        $queued->setObject ( $message );

        $this->em
                ->persist ( $queued );
        $this->em
                ->flush ( );
    }

    public function getMessages ( $end_queue, $start_queue )
    {
        $timeUTC = time ( );
        return $this->_getMessages ( $end_queue, $start_queue, $timeUTC );
    }

    protected function _getMessages ( $end_queue, $start_queue, $timeUTC )
    {

        $query = $this->em
                      ->getRepository ( 'StrSocialSmsQueueBundle:BufferMessage' )
                      ->createQueryBuilder ( 'm' )
                      ->where ( "CONVERT_TZ(:now,SYSTEM,timezone) >= :endqueue" )
                      ->andWhere ( 
                        "CONVERT_TZ(:now,SYSTEM,timezone) <= :startqueue" )
                ->setParameter ( 'now', date ( 'Y-m-d H:i:s', $timeUTC ) )
                ->setParameter ( 'endqueue',
                        date ( 'Y-m-d ', $timeUTC ) . date ( 'H:i:s',
                                        $end_queue ) )
                ->setParameter ( 'startqueue',
                        date ( 'Y-m-d ', $timeUTC )
                                . date ( 'H:i:s', $start_queue ) );

        foreach ( $query->getResult ( ) as $k => $qmsg )
        {
            $fetched[$k] = $qmsg->getObject ( );
            $this->em
                    ->remove ( $qmsg );
        }
        $this->em
                ->flush ( );

        return $fetched;
    }

}
