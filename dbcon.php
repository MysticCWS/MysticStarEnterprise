<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)
    ->withServiceAccount('mysticstarenterprise-firebase-adminsdk-zo1pu-839fb8bee7')
    ->withDatabaseUri('https://mysticstarenterprise-default-rtdb.asia-southeast1.firebasedatabase.app/');

$database = $factory->createDatabase();