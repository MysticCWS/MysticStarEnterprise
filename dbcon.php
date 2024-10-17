<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Auth\UserQuery;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Contract\Storage;

//Realtime Database
$factory = (new Factory)
    ->withServiceAccount('mysticstarenterprise-firebase-adminsdk-zo1pu-839fb8bee7.json')
    ->withDatabaseUri('https://mysticstarenterprise-default-rtdb.asia-southeast1.firebasedatabase.app/');

$database = $factory->createDatabase();

//Auth Users
$auth = $factory->createAuth();

//Storage
$storage = (new Factory())
    ->withServiceAccount('mysticstarenterprise-firebase-adminsdk-zo1pu-839fb8bee7.json')
    ->withDefaultStorageBucket('mysticstarenterprise.appspot.com')
    ->createStorage();

$storageClient = $storage->getStorageClient();
$defaultBucket = $storage->getBucket();
$anotherBucket = $storage->getBucket('another-bucket');


//Check if a URL exists
function URLcheck($url){
   $headers=get_headers($url);
   return stripos($headers[0],"200 OK")?true:false;
}