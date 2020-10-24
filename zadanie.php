<?php

require_once 'core\User.php';
require_once 'vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use Core\User;

/*
 * returns new User Object
 */
function createUser($obj) : User
{
    $company = $obj->company;
    $address = $obj->address;

    $user = new User($obj->id, $obj->name, $obj->username, $obj->email, $obj->phone, $obj->website);
    $user->setCompany($company->name, $company->catchPhrase, $company->bs);
    $user->setAddress($address->street, $address->suite, $address->city, $address->zipcode, $address->geo->lat, $address->geo->lng);

    $user->getDomain();
    return $user;
}

/*
 * makes new Database;
 */
function makeDB(String $mysql_host, String $username, String $password)
{
    try{
        $conn = new PDO('mysql:host=' . $mysql_host . ";charset=utf8", $username, $password);
        $sql = "
            CREATE DATABASE demo;
            USE demo;
            CREATE TABLE usersData(
                email varchar(256) PRIMARY KEY NOT NULL,
                amount int DEFAULT 0
            );
            ";
        $conn->exec($sql);

    } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
}

/*
 * Returns connection object
 */
function getConnection(String $mysql_host, String $username, String $password){
    return new PDO('mysql:host=' . $mysql_host . ";dbname=demo;charset=utf8", $username, $password);
}

/*
 * returns new User
 */
function getUser(String $url) : User
{
    $json = file_get_contents($url);
    $obj = json_decode($json);

    return createUser($obj);
}

/*
 * Return Array with all of users
 */
function getAllUsers(String $url)
{
    $json = file_get_contents($url);
    $obj = json_decode($json);

    $users = [];

    foreach ($obj as $user) {
        $users[] = createUser($user);
    }

    return $users;
}

/*
 * cheks if email exist in database, returns bool
 */
function checkEmail($conn, $email){
    $stmt = $conn->prepare("SELECT * from demo.usersData WHERE email LIKE ?");
    $stmt->bindParam(1, $email, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);

}

/*
 * responsible for collecting data and updating information about users
 */
function collectData($conn, $url){
    $users = getAllUsers($url);
    foreach ($users as $user) {
        if(checkEmail($conn, $user->email)){
            $stmt = $conn->prepare("UPDATE demo.usersData SET amount = 1 WHERE email LIKE ?");
            $stmt->bindParam(1, $user->email, PDO::PARAM_STR);
            $stmt->execute();
        }
        else{
            $stmt = $conn->prepare("INSERT INTO demo.usersData VALUES (?,0)");
            $stmt->bindParam(1, $user->email, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
}


$user = getUser('https://jsonplaceholder.typicode.com/users/1');

$mysql_host = 'localhost';
$username = 'root';
$password = '';

makeDB($mysql_host, $username, $password);

collectData(getConnection($mysql_host, $username, $password),'https://jsonplaceholder.typicode.com/users');

echo '<div><h4>2. Domena :</h4>'.$user->getDomain().'</div>';

echo '<div><h4>3.1 JSON :</h4>'.$user->getPersonData().'</div>';

echo '<div><h4>3.2 QR : </h4><img src="'.(new QRCode)->render($user->getPersonData()).'" alt="QR Code" /></div>';
