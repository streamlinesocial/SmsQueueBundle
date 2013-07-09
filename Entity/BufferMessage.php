<?php

namespace StrSocial\Bundle\SmsQueueBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="str_social_sms_queue_buffer",indexes={@ORM\Index(name="sent_idx", columns={"sent"})})
 * @ORM\HasLifecycleCallbacks
 */
class BufferMessage
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
     * @ORM\Column(type="string", length=160)
     */
    protected $text;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $sent = FALSE;

    /**
     * @ORM\Column(type="integer")
     */
    protected $count = 0;
    
    /**
     * @ORM\Column(type="datetime", name="updated_at")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

   
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set phone_number
     *
     * @param string $phoneNumber
     * @return BufferMessage
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phone_number = $phoneNumber;

        return $this;
    }

    /**
     * Get phone_number
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return BufferMessage
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set sent flag
     * 
     * @param boolean $sent
     * @return BufferMessage
     */
    public function setSent($sent)
    {
        $this->sent = (boolean) $sent;

        return $this;
    }

    /**
     * 
     * @return boolean
     */
    public function getSent()
    {
        return (boolean) ($this->sent);
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \Datetime('now');
        
        return $this;
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \Datetime('now');
        
        return $this;
    }
    
    /**
     *
     * @return \Datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * 
     * @return number
     */
    public function getCount() {
        return $this->count;
    }
    
    /**
     * Increment the count of the message
     * 
     * @return BufferMessage
     */
    public function increaseCount() {
        $this->count++;
        
        return $this;
    }
}
