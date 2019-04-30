<?php
// include database and object files
include_once '../db/database.php';
include_once '../Models/user.php';
include_once '../Models/conference.php';

include_once 'getFullData.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$user = new User($db);
$conference = new Conference($db);

// we first retrieve user's credentials
$user->email_admin = isset($_GET['email_admin']) ? $_GET['email_admin'] : die();
$conference->email_admin = $user->email_admin;
$user->pwd = base64_encode(isset($_GET['pwd']) ? $_GET['pwd'] : die());
$conference->short_name_conf =
    isset($_GET['short_name_conf']) ? $_GET['short_name_conf'] : die();

// then we check if the user's email and password match
$stmt = $user->login();
if ($stmt->rowCount() > 0) {
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_arr = array(
        "status" => true,
        "message" => "Successfully Login!",
        "username" => $row['email_admin']
    );
    print_r(json_encode($user_arr));
    /* next if the conference exist the user should be able to edit it.
    since we are using the same form to create and update conferences,
    all the input field should be filled with the original value of the conference,
    and then append the argument value pair to the url so the view can grab them
    using inline php echo statement inside the value attribute of the html form
    */
    if ($conference->exist()) {
        $conf_arr = json_decode($conference->getConference(), true)[0];
        $conference->id_conf = $conf_arr['id_conf'];
        $url = "Location: ../Views/addConference.php?email_admin="
            . $user->email_admin . "&full_name_conf="
            . $conf_arr['full_name_conf'] . "&short_name_conf="
            . $conference->short_name_conf . "&email_conf="
            . $conf_arr['email_conf'] . "&website="
            . $conf_arr['website'] . "&description="
            . $conf_arr['description'] . "&id_edition=" . $conference->getLastEdition()
            . "&email_admin=" . $conference->email_admin;
        $url .= "&id_conf=" . $conference->id_conf;



        $topic_arr = getTopicsByConf($conference->id_conf);
        foreach ($topic_arr as $index_topic => $topic) {
            $url .= "&topic" . ($index_topic + 1) . "=" . $topic;
        }
        $speaker_arr = getSpeakersByConf($conf_arr['id_conf']);
        foreach ($speaker_arr as $index_speaker => $speaker) {
            $url .= "&speaker" . ($index_speaker + 1) . "=" . $speaker;
        }
        $location_arr = getLocationsByConf($conference->id_conf, $conference->getLastEdition())[0];
        $url .= "&name_location=" . $location_arr['name_location'] . "&url_location=" . $location_arr['url_location'];
        $activities_arr = getActivitiesByConf($conference->id_conf, $conference->getLastEdition());
        foreach ($activities_arr as $index_activity => $activity) {
            $url .= "&activity" . ($index_activity + 1) . "=" . $activity['activity_name']
                . "&date" . ($index_activity + 1) . "=" . $activity['key_date'];
        }
        // redirection using the url
        Header($url);
        // otherwise we redirect to empty form were we fill only the short name that
        // the user has entered in the previous form.
        // the user email should also be passed to the view so the corresponding
        //controller can assign it to the conference being created.
    } else {
        Header("Location: ../Views/addConference.php?short_name_conf="
            . $conference->short_name_conf . "&email_admin="
            . $conference->email_admin);
    }

} else {
    $user_arr = array(
        "status" => false,
        "message" => "Invalid E-mail or Password!",
    );
    print_r(json_encode($user_arr));
}
// make it json format

?>