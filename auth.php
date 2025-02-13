<?php
require_once "jsonstorage.php";
class Auth
{
    private $users;
    public function __construct()
    {
        $this->users = new JsonStorage("data/users.json");
    }
    public function register($user)
    {
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        $user['password_again'] = password_hash($user['password_again'], PASSWORD_DEFAULT);
        return $this->users->insert((object) $user);
    }
    public function user_exists($username)
    {
        $users = $this->users->filter(function ($user) use ($username) {
            return ((array) $user)['username'] === $username;
        });
        return count($users) >= 1;
    }
    public function login($user)
    {
        $_SESSION["user"] = $user;
    }
    public function check_credentials($email, $password)
    {
        $users = $this->users->filter(function ($user) use ($email) {
            return ((array) $user)['email'] === $email;
        });
        if (count($users) === 1) {
            $user = (array) array_values($users)[0];
            return password_verify($password, $user["password"])
            ? $user
            : false;
        }
        return false;
    }
    public function is_authenticated()
    {
        return isset($_SESSION["user"]);
    }
    public function logout()
    {
        unset($_SESSION["user"]);
    }
}