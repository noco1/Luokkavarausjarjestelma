<?php
//functions.php

//Luo CSRF-tokenin generointi ja validointi.
//Lisää funktio require_login() suojaamaan sivut.

session_start();

function generate_csrf_token()
{
    if (empty($_SESSION['csrf_token']))
    {
        $_SESSION['csrf_token'] = bind2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) &&
    hash_equals($_SESSION['csrf_token'], $token);
}

function is_logged_in()
{
    return !empty($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in())
    {
        header('Location: login.php');
        exit;
    }
}