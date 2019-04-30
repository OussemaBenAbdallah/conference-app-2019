<?php

// get database connection
include_once '../db/database.php';

// instantiate user object
include_once '../models/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

/*  public $created_admin;
    public $email_admin;
    public $first_name;
	public $last_name;
	public $pwd;
    public $tel; */

// set user property values
$user->created_admin = date('Y-m-d H:i:s');
$user->email_admin = $_GET['email_admin'];
$user->first_name = $_GET['first_name'];
$user->last_name = $_GET['last_name'];
$user->pwd = base64_encode($_GET['pwd']);
$user->tel = $_GET['tel'];
// create the user
if ($user->addUser()) {
    $user_arr = array(
        "status" => true,
        "message" => "Successfully Signup!",
        //"id" => $user->id,
        "created_admin" => $user->created_admin,
        "email_admin" => $user->email_admin,
        "first_name" => $user->first_name,
        "last_name" => $user->last_name,
        "pwd" => $user->pwd,
        "tel" => $user->tel

    );
} else {
    $user_arr = array(
        "status" => false,
        "message" => "Username already exists!"
    );
}
print_r(json_encode($user_arr));
Header("Location: ../Views/sign-in.php?email_admin=".$user->email_admin);