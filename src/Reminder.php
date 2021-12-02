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
     * @ORM\Column(type="datetime",  nullable=false)
     * @var DateTime
     */
    private $date_created;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var DateTime
     */
    private $remind_date;

    /**
     * Experimental, and self explanatory
     * @ORM\Column(type="boolean", nullable=true)
     * @var bool
     */
    private $is_checked;

    /**
     * Experimental, stores the id of the course that's related to this reminder
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $target_course;

    /**
     * Experimental, 0 means not recurring, 1 - 7 refers to the day of the week where it is recurring
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $is_recurring;

    public function __construct()
    {
        $this->date_created = new DateTime($datetime = "now", new DateTimeZone('Asia/Manila'));
    }

    // Class Methods

    /**
     * Take a guess on what this does.
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * Returns the owner of the reminder.
     */
    public function get_user(): User
    {
        return $this->user;
    }

    /**
     * Sets the owner of the reminder.
     */
    public function set_user(User $user)
    {
        $this->user = $user;
    }

    /**
     * Gets the type of the reminder.
     */
    public function get_type(): string
    {
        return $this->type;
    }

    /**
     * Sets the type of the reminder.
     */
    public function set_type(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Gets the title of the reminder.
     */
    public function get_title(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title of the reminder.
     */
    public function set_title(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Gets the message of the reminder.
     */
    public function get_message(): ?string
    {
        return $this->message;
    }

    /**
     * Sets the message of the reminder.
     */
    public function set_message(string $message): void
    {
        $this->message = $message;
    }

    /**
     * Gets the date of the reminder when it was created.
     */
    public function get_date_created(): DateTime
    {
        return $this->date_created;
    }

    /**
     * Sets the date of the reminder when it was created.
     */
    public function set_date_created(DateTime $date_created): void
    {
        $this->date_created = $date_created;
    }

    /**
     * Gets the date of the reminder for when it's supposed to remind.
     */
    public function get_remind_date(): DateTime
    {
        return $this->remind_date;
    }

    /**
     * Sets the date of the reminder for when it's supposed to remind.
     */
    public function set_remind_date(DateTime $remind_date): void
    {
        $this->remind_date = $remind_date;
    }

    /**
     * Gets the value on whether the reminder is checked or not.
     */
    public function get_is_checked(): ?bool
    {
        return $this->is_checked;
    }

    /**
     * Sets the value on whether the reminder is checked or not.
     */
    public function set_is_checked(bool $is_checked) : void
    {
        $this->is_checked = $is_checked;
    }

    /**
     * Gets the target course of the reminder.
     */
    public function get_target_course() : ?int
    {
        return $this->target_course;
    }

    /**
     * Sets the target course of the reminder.
     */
    public function set_target_course(int $target_course): void
    {
        $this->target_course = $target_course;
    }

    /**
     * Gets the value on whether the reminder is recurring or not.
     */
    public function get_is_recurring() : ?int
    {
        return $this->is_recurring;
    }

    /**
     * Sets the value on whether the reminder is recurring or not.
     */
    public function set_is_recurring(int $is_recurring): void
    {
        $this->is_recurring = $is_recurring;
    }
}
