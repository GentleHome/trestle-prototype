<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="reminders")
 */
class Reminder
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", fetch="EAGER", inversedBy="reminders")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=12, nullable=false)
     * @var string
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     * @var string
     */
    private $message;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var DateTime
     */
    private $date_created;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var DateTime
     */
    private $remind_date;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @var bool
     */
    private $is_checked;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $target_course;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $is_recurring;



    // Class Methods

    /**
     * Take a guess on what this does.
     */
    public function get_id(): int
    {
        return $this->id;
    }

    
}
