<?php

namespace StrSocial\Bundle\SmsQueueBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="str_social_sms_queue_queued_message")
 */
class QueuedMessage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    protected $id;

    /**
     * Format as E.164
     * 
     * @ORM\Column(type="string", length=15)
     */
    protected $phone_number;

    /**
     * @ORM\Column(type="string")
     */
    protected $timezone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $custom_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $custom_value;

    /**
     * @ORM\Column(type="object")
     */
    protected $object;

    /**
     * Get id
     *
     * @return integer 
     */

    public function getId ( )
    {
        return $this->id;
    }

    /**
     * Set phone_number
     *
     * @param string $phoneNumber
     * @return QueuedMessage
     */

    public function setPhoneNumber ( $phoneNumber )
    {
        $this->phone_number = $phoneNumber;

        return $this;
    }

    /**
     * Get phone_number
     *
     * @return string 
     */

    public function getPhoneNumber ( )
    {
        return $this->phone_number;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return QueuedMessage
     */

    public function setCreated ( $created )
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */

    public function getCreated ( )
    {
        return $this->created;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     * @return QueuedMessage
     */

    public function setTimezone ( $timezone )
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get timezone
     *
     * @return string 
     */

    public function getTimezone ( )
    {
        return $this->timezone;
    }

    /**
     * Set object
     *
     * @param \stdClass $object
     * @return QueuedMessage
     */

    public function setObject ( $object )
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return \stdClass 
     */

    public function getObject ( )
    {
        return $this->object;
    }

    /**
     * Set custom_type
     *
     * @param string $customType
     * @return QueuedMessage
     */

    public function setCustomType ( $customType )
    {
        $this->custom_type = $customType;

        return $this;
    }

    /**
     * Get custom_type
     *
     * @return string 
     */

    public function getCustomType ( )
    {
        return $this->custom_type;
    }

    /**
     * Set custom_value
     *
     * @param string $customValue
     * @return QueuedMessage
     */

    public function setCustomValue ( $customValue )
    {
        $this->custom_value = $customValue;

        return $this;
    }

    /**
     * Get custom_value
     *
     * @return string 
     */

    public function getCustomValue ( )
    {
        return $this->custom_value;
    }
}
