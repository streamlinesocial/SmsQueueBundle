<?php

namespace StrSocial\Bundle\SmsQueueBundle\Component;

/**
 *
 * 
 * Internally: Basically a Mediator for Sender, Queue and GateKeeper. 
 * Keeps Control of all interactions and communication
 * between these objects.
 * 
 * @author nachinius
 *
 */
class Control
{

    protected $sender;
    protected $queue;
    protected $gatekeeper;

    public function __construct ( 
            Queue $queue,
            GateKeeper $gatekeeper,
            Sender $sender )
    {

        $this->queue = $queue;
        $this->gatekeeper = $gatekeeper;
        $this->sender = $sender;
    }

    /**
     * Enter the Message into the Mediator for processing
     * 
     * @param MessageInterface $message
     */

    public function send ( MessageInterface $message )
    {
        // to send or not to send
        if ( $this->gatekeeper
                  ->canSend ( $message ) )
        {

            if ( $message->isValid ( ) )
            {
                $this->sender
                        ->send ( $message );
            }
            else // invalid message
            {
                //drop
            }
        }
        else
        {
            $this->queue
                    ->enqueue ( $message );
        }
    }

    /**
     * Find all messages in the queue that can be send now
     * and send them.
     */

    public function processQueue ( )
    {
        $ending_queue_time = $this->gatekeeper
                                  ->getEndQueueTime ( );
        $starting_queue_time = $this->gatekeeper
                                    ->getStartQueueTime ( );

        foreach ( $this->queue
                       ->getMessages ( $ending_queue_time, $starting_queue_time ) as $message )
        {
            $this->send ( $message );
        }
    }
}
