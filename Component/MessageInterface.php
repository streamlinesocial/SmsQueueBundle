<?php

namespace StrSocial\Bundle\SmsQueueBundle\Component;
/**
 * 
 * @author nachinius
 *
 */
interface MessageInterface
{

    public function getPhoneNumber ( );

    public function getTextMessage ( );

    public function getTimeZone ( );

    public function isValid ( );

    public function getMessageTypeKey ( );

    public function getMessageTypeValue ( );
}
