<?php
/*       incomlited api !!!!
 insert into all table have relation with confirance,
 this api will be called when we click submit the page of
 formConf to add new conferance with all details  */

// get database connection
include_once '../db/database.php';

// instantiate conference object
include_once '../Models/conference.php';
include_once '../Models/Topic.php';
include_once '../Models/ConfTopic.php';
include_once '../Models/speaker.php';
include_once '../Models/ConfSpeaker.php';
include_once '../Models/Location.php';
include_once '../Models/Activity.php';
include_once '../Models/ConfDateLoc.php';



$database = new Database();
$db = $database->getConnection();

$conference = new Conference($db);
$topic = new Topic($db);
$speaker = new Speaker($db);
$conf_topic = new ConfTopic($db);
$conf_speaker = new ConfSpeaker($db);
$location = new Location($db);
$activity = new Activity($db);

$confDateLoc = new ConfDateLoc($db);


/*  public $id_conf;
    public $email_admin;
    public $full_name_conf;
    public $short_name_conf;
    public $email_conf;
    public $website;
    public $created_conf;
    public $description; */

// set conference property values
$conference->email_admin = $_GET['email_admin'];
$conference->full_name_conf = $_GET['full_name_conf'];
$conference->short_name_conf = $_GET['short_name_conf'];//date('Y-m-d H:i:s');
$conference->email_conf = $_GET['email_conf'];
$conference->website = $_GET['website'];//date('Y-m-d H:i:s');
$conference->created_conf = date('Y-m-d H:i:s');
$conference->description = $_GET['description'];
$location->name_location = $_GET['name_location'];
$location->url_location = $_GET['url_location'];

$conference->getIDConference();

if($location->addLocation()){
    echo "location success ";
} else {
    $location->id_location = $location->getIdByName();
}

// create the conference
if ($conference->updateConference()) {

    $conference_arr = array(
        "status" => true,
        "message" => "Successfully add!",
        "email_admin" => $conference->email_admin,
        "full_name_conf" => $conference->full_name_conf,
        "short_name_conf" => $conference->short_name_conf,
        "email_conf" => $conference->email_conf,
        "website" => $conference->website,
        "created_conf" => $conference->created_conf,
        "description" => $conference->description
    );
    echo 'conference added successfully';
} else {
    $conference_arr = array(
        "status" => false,
        "message" => "Conference already exists!"
    );
}
//add topic
$i = 1;
$conf_topic->deleteConfTopic($conference->id_conf);
while (isset($_GET['topic' . $i]) and strlen($_GET['topic' . $i]) > 0) {
    $topic->topic_name = $_GET['topic' . $i];
    
    if ($topic->addTopic()) {
        echo 'topic success';
    } else {
        $topic->id_topic = $topic->getIdByName();
        echo 'topic already exists'.$topic->id_topic;
    }
    
    $conf_topic->addConfTopic($conference->id_conf, $topic->id_topic);
    $i++;
}
$i = 1;
$conf_speaker->deleteConfSpeaker($conference->id_conf);
while (isset($_GET['speaker' . $i]) and strlen($_GET['speaker' . $i]) > 0) {
    $speaker->full_name_speaker = $_GET['speaker' . $i];
    if ($speaker->addSpeaker()) {
        echo 'speaker success';
    } else {
        $speaker->id_speaker = $speaker->getIdByName();
        echo 'speaker already exists';
    }
    $conf_speaker->addConfSpeaker($conference->id_conf, $speaker->id_speaker);
    $i++;
}
$i = 1;
$confDateLoc->deleteConfDateLoc($conference->id_conf);
while (isset($_GET['activity' . $i]) and strlen($_GET['activity' . $i]) > 0) {
    $activity->activity_name= $_GET['activity' . $i];
    if ($activity->addActivity()) {

        echo 'activity success';
    } else {
        $activity->id_activity = $activity->getIdByName();
        echo 'activity already exists'.'**'.$activity->id_activity.'**';
    }

    $confDateLoc->id_conf =$conference->id_conf;
    $confDateLoc->id_edition =$_GET['id_edition'];
    $confDateLoc->id_location =$location->getIdByName();
    $confDateLoc->id_activity =$activity->id_activity;
    $confDateLoc->key_date =$_GET['date'.$i];

    echo '***'.$confDateLoc->addConfDateLoc().'***';

    $i++;
}


//add topic_conf


print_r(json_encode($conference_arr));