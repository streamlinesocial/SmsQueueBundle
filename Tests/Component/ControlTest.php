<?php

namespace StrSocial\Bundle\SmsQueueBundle\Tests\Component;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use StrSocial\Bundle\SmsQueueBundle\Component\Sender;
use StrSocial\Bundle\SmsQueueBundle\Component\GateKeeper;
use StrSocial\Bundle\SmsQueueBundle\Component\Queue;
use StrSocial\Bundle\SmsQueueBundle\Component\Control;
use StrSocial\Bundle\SmsQueueBundle\Component\Message;

class ControlTest extends WebTestCase
{
    protected static $kernel;
    protected static $container;
    
    public static function setUpBeforeClass()
    {
        self::$kernel = static::createKernel();
        self::$kernel->boot();
    
        self::$container = self::$kernel->getContainer();
    }
    
    public function get($serviceId)
    {
        return self::$kernel->getContainer()->get($serviceId);
    }
    
    public function testPuttingAMessageIntoPersistance()
    {
        $em = static::get('doctrine.orm.entity_manager');
        $fakeSender = new fakeSender($em,1);
        $queue = new Queue($em);
        $gatekeeper_rejector = new fakeGateKeeper(FALSE);
        $control = new Control($queue, $gatekeeper_rejector, $fakeSender);
        
        $msg = new Message();
        $msg->setPhoneNumber( rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).
            rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9));
        $msg->setTextMessage('Text Message from heaven '.rand());
        
        $control->send($msg);
    }
}

class fakeSender extends Sender
{
    public function send()
    {
        return TRUE;
    }
}

class fakeQueue extends Queue
{
    public function enqueue()
    {
        return TRUE;
    }
    public function getMessages()
    {
        return array();
    }
}

class fakeGateKeeper extends GateKeeper
{
    public function __construct($value) {
        $this->canSend = $value;
    }
    public function canSend() 
    {
        return $this->canSend;
    }
}
