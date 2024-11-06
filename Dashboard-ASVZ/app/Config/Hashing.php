<?php

use Illuminate\Support\Facades\Hash;

/**
 * Versleutel een wachtwoord met behulp van bcrypt.
 *
 * @param  string  $password
 * @return string
 */
function hashPassword($password)
{
    return Hash::make($password);
}

/**
 * Controleer of een wachtwoord overeenkomt met de gehashte versie.
 *
 * @param  string  $password
 * @param  string  $hashedPassword
 * @return bool
 */
function verifyPassword($password, $hashedPassword)
{
    return Hash::check($password, $hashedPassword);
}

/**
 * Controleer of een wachtwoord overeenkomt met de gehashte versie, met extra beveiliging (tijd-gebaseerde vergelijking).
 *
 * @param  string  $password
 * @param  string  $hashedPassword
 * @return bool
 */
function verifyPasswordWithStrictComparison($password, $hashedPassword)
{
    return Hash::check($password, $hashedPassword);
}
