<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable="false")
     * @ORM\GeneratedValue
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, unique=true, nullable="false")
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string",  nullable="false")
     * @var string
     */
    private $password_hash;

    /**
     * @ORM\Column(type="string", nullable="true")
     * @var string
     */
    private $google_token;

    /**
     * @ORM\Column(type="string", nullable="true")
     * @var string
     */
    private $canvas_token;


    // Class Methods
    /**
     * Returns the username of the User object.
     */
    public function get_username(): string
    {
        return $this->username;
    }

    /**
     * Takes a hash_checker to hash and check if the current password hash matches with the inputed password
     */
    public function authenticate(string $input_password, callable $hash_checker): bool
    {
        return $hash_checker($input_password, $this->password_hash);
    }

    /**
     * Takes in the password string, and hashes it with the callable hasher
     */
    public function set_password(string $password, callable $hasher): void
    {
        $this->password_hash = $hasher($password);
    }

    /**
     * Sets the Google Classroom token lol
     */
    public function set_google_token(string $google_token): void
    {
        $this->google_token = $google_token;
    }

    /**
     * Sets the Canvas LMS token lol
     */
    public function set_canvas_token(string $canvas_token): void
    {
        $this->canvas_token = $canvas_token;
    }

    /**
     * Gets the Google Classroom token lol
     */
    public function get_google_token(): string
    {
        return $this->google_token;
    }

    /**
     * Gets the Canvas LMS token lol
     */
    public function get_canvas_token(): string
    {
        return $this->canvas_token;
    }
}
