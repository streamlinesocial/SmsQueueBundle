<?php

namespace StrSocial\Bundle\SmsQueueBundle\Component;

/**
 * Decides if a message can be sent or not by looking at the 
 * Start Queue Time and End Queue Time.
 * 
 * It takes in account the timezone of the message.
 * It's assuming that server is in UTC
 * 
 * @author nachinius
 *
 */
class GateKeeper {
    
    protected $start_time;
    protected $end_time;
    
    public function __construct(Array $queue_times)
    {
        // @TODO validate arguments
        
        // 23:00:00
        $this->start_time = $queue_times['start'];
        // 09:00:00
        $this->end_time = $queue_times['end'];
    }
    
    public function getStartQueueTime()
    {
        return $this->start_time;
    }
    
    public function getEndQueueTime()
    {
        return $this->end_time;
    }
    
    /**
     * Find whether or not this $message should be sent now or
     * set in the queue
     * 
     * @param MessageInterface $message
     * 
     * @return boolean
     */
    public function canSend(MessageInterface $message)
    {
        $timezone = $message->getTimeZone();
        $timestampUTC = time();
        
        return self::_canSend($this->start_time, $this->end_time, $timezone, $timestampUTC);
    }
    
    /**
     * Determine if given the time of the queue, the timezone and the time at UTC
     * the an arbitrary message is ok to be sent now.
     * 
     * 
     * 
     * @param string_time $start eg "21:00:00" 
     * @param string_time $end eg "09:00:00"
     * @param string $timezone eg "+03:00"
     * @param time $timestampUTC
     * @return boolean
     */
    protected static function _canSend($start, $end, $timezone, $timestampUTC)
    {
        $timestamp = self::getTimestampForTimezone($timestampUTC, $timezone);
        $date = self::getDateForTimezone($timestampUTC, $timezone);
        $local_start_stamp = strtotime("$date $start");
        $local_end_stamp = strtotime("$date $end");
        
        // example:
        // 0 start of day
        // 8am end queueing time
        // 9pm start queueing time
        // 12am end of day
        
        // from 0 -> 8am => do not send
        // from 8am -> 9pm => send
        // from 9pm -> 12am => do not send
        
        // too early on the morning
        if($timestamp < $local_end_stamp) {
            return FALSE;
        } 
        // during the 'day'
        elseif($timestamp <= $local_start_stamp && $timestamp >= $local_end_stamp)
        {
            return TRUE;
        }
        // too late on night
        elseif($timestamp > $local_start_stamp) 
        {
            return FALSE;
        }
    }
    
    /**
     * Knowing the timestamp at UTC, get the timestamp at $timezone
     * 
     * @param unknown_type $timestampUTC
     * @param unknown_type $timezone
     * @return number
     */
    protected static function getTimestampForTimezone($timestampUTC, $timezone)
    {
        // @TODO validate arguments
        
        $split = explode(':',$timezone);
        $n = count($split);
        if($n > 1) 
        {
            $minutes = $split[1];
        } else {
            $minutes = 0;
        }
        
        $hours = $split[0];
        $sign = ($hours >= 0) ? 1 : -1;
        // eliminate the sign
        $hours = $sign * $hours;
        
        $offset = $sign * (60 * $hours + $minutes) * 60;
        
        $localtimestamp = $timestampUTC + $offset;
        
        return $localtimestamp;
    }
    
    protected static function getDateForTimezone($timestampUTC, $timezone)
    {
        return date('Y-m-d',self::getTimestampForTimezone($timestampUTC, $timezone));
    }
}
