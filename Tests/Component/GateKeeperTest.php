<?php

namespace StrSocial\Bundle\SmsQueueBundle\Tests\Component;
use StrSocial\Bundle\SmsQueueBundle\Component\Sender;
use StrSocial\Bundle\SmsQueueBundle\Component\GateKeeper;
use StrSocial\Bundle\SmsQueueBundle\Component\Queue;
use StrSocial\Bundle\SmsQueueBundle\Component\Control;
use StrSocial\Bundle\SmsQueueBundle\Component;

class GateKeeperTest extends \PHPUnit_Framework_TestCase
{

    public function testGetGateKeeper ( )
    {
        $gk = new GateKeeper ( array(
            'start' => '23:00', 'end' => '08:00'
        ));

        return $gk;
    }

    /**
     * @depends testGetGateKeeper
     */

    public function testCanSendTimeLogicComparison ( $gk )
    {
        $method = new \ReflectionMethod ( 
                'StrSocial\Bundle\SmsQueueBundle\Component\GateKeeper',
                '_canSend');
        $method->setAccessible ( TRUE );

        $keys = array(
            'start_queue_time', 'end_queue_time', 'tz', 'utc', 'expectation'
        );

        $firstSecOfDay = strtotime ( '2013-03-13 00:00:01' );
        $values[] = array(
            '21:00', '09:00', '+00:00', strtotime ( '00:00' ), false
        );
        $values[] = array(
            '21:00', '09:00', '+00:00', strtotime ( '08:59:59' ), false
        );
        $values[] = array(
            '21:00', '09:00', '+00:00', strtotime ( '09:00:00' ), true
        );
        $values[] = array(
            '21:00', '09:00', '+00:00', strtotime ( '09:00:01' ), true
        );
        $values[] = array(
            '21:00', '09:00', '+00:00', strtotime ( '20:59:59' ), true
        );
        $values[] = array(
            '21:00', '09:00', '+00:00', strtotime ( '21:00:00' ), true
        );
        $values[] = array(
            '21:00', '09:00', '+00:00', strtotime ( '21:00:01' ), false
        );
        $values[] = array(
            '21:00', '09:00', '+00:00', strtotime ( '23:59:59' ), false
        );
        $values[] = array(
            '21:00', '09:00', '+00:00', strtotime ( '24:00:00' ), false
        );

        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '00:00' ), false
        );
        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '08:59:59' ), false
        );
        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '09:00:00' ), false
        );
        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '09:00:01' ), false
        );
        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '10:00:00' ), true
        );
        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '10:00:01' ), true
        );
        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '20:59:59' ), true
        );
        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '21:00:00' ), true
        );
        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '21:00:01' ), true
        );
        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '21:59:59' ), true
        );
        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '22:00:00' ), true
        );
        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '22:00:01' ), false
        );
        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '23:59:59' ), false
        );
        $values[] = array(
            '21:00', '09:00', '-01:00', strtotime ( '24:00:00' ), false
        );

        foreach ( $values as $k => $v )
        {
            $a = array_combine ( $keys, $v );
            $canSend = $method->invoke ( $gk, $a['start_queue_time'],
                              $a['end_queue_time'], $a['tz'], $a['utc'] );
            $this->assertEquals ( $a['expectation'], $canSend,
                            __FUNCTION__ . " value $k: "
                                    . print_r ( $values[$k], 1 )
                                    . ' which is for '
                                    . date ( 'H:i:s', $a['utc'] ) );
        }
    }
}
