<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, unique=true, nullable=false)
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string",  nullable=false)
     * @var string
     */
    private $password_hash;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $google_token;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $canvas_token;

    /**
     * @ORM\OneToMany(targetEntity="Reminder", mappedBy="user", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     */
    private $reminders;

    public function __construct()
    {
        $this->reminders_list = new ArrayCollection();
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
     * Returns the username of the User object.
     */
    public function get_username(): string
    {
        return $this->username;
    }

    /**
     * Sets the username of the User object.
     */
    public function set_username(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Takes a hash_checker to hash and check if the current password hash matches with the inputed password
     */
    public function authenticate(string $input_password): bool
    {
        return password_verify($input_password, $this->password_hash);
    }

    /**
     * Takes in the password string, and hashes it with the callable hasher
     */
    public function set_password(string $password): void
    {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Gets the Google token lol
     */
    public function get_google_token(): array
    {
        return json_decode($this->google_token);
    }

    /**
     * Sets the Google token lol
     */
    public function set_google_token(array $google_token): void
    {
        $this->google_token = json_encode($google_token);
    }

    /**
     * Gets the Canvas LMS token lol
     */
    public function get_canvas_token(): string
    {
        return $this->canvas_token;
    }

    /**
     * Sets the Canvas LMS token lol
     */
    public function set_canvas_token(string $canvas_token): void
    {
        $this->canvas_token = $canvas_token;
    }

    /**
     * Gets the reminders list lol
     */
    public function get_reminders()
    {
        return $this->reminders;
    }

    /**
     * Sets the reminders list lol
     */
    public function set_reminders(ArrayCollection $reminders): void
    {
        $this->reminders = $reminders;
    }
}