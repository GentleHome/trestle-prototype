<?php
use Doctrine\ORM\EntityManager;

function get_logged_in_user(EntityManager $entityManager): ?User{
    if (isset($_SESSION['user_id'])) {
        $user = $entityManager->find("User", $_SESSION['user_id']);
        return $user;
    } else {
        return null;
    }
}