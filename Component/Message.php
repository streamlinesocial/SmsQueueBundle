<?php

namespace StrSocial\Bundle\SmsQueueBundle\Component;

/**
 * Simple class that implements MessageInterface trivialy.
 * It's always valid.
 * 
 * $msg = new Message();
 * $msg->setPhoneNumber('555-555-5555');
 * $msg->text('Hola');
 * ...
 * Control->send($msg);
 * 
 * @author nachinius
 *
 */
class Message implements MessageInterface
{

    private $phone_number;
    private $text;
    private $timezone = '0';
    private $type_key = '';
    private $type_value = '';

    public function setPhoneNumber ( $val )
    {
        $this->phone_number = $val;
    }

    public function getPhoneNumber ( )
    {
        return $this->phone_number;
    }

    public function setTextMessage ( $val )
    {
        $this->text = $val;
    }

    public function getTextMessage ( )
    {
        return $this->text;
    }

    public function setTimeZone ( $val )
    {
        $this->timezone = $val;
    }

    public function getTimeZone ( )
    {
        return $this->timezone;
    }

    public function setMessageType ( $val )
    {
        $this->type_key = $val;
    }

    public function getMessageTypeKey ( )
    {
        return $this->type_key;
    }

    public function setMessageTypeValue ( $val )
    {
        $this->type_value = $val;
    }

    public function getMessageTypeValue ( )
    {
        return $this->type_value;
    }

    public function isValid ( )
    {
        return TRUE;
    }
}
