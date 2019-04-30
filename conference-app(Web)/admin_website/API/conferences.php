<?php

// get database connection
include_once '../db/database.php';

// instantiate conference object
include_once '../models/conference.php';
include_once 'getFullData.php';

$database = new Database();
$db = $database->getConnection();

$conference = new Conference($db);
// create json array containing all conferences
$full_arr = json_decode($conference->getAllConference(), true);
/*loop over all conference and add its component objects*/
foreach ($full_arr as $index => $value) {
    /* add speakers */
    $full_arr[$index]['speakers'] = getSpeakersByConf($full_arr[$index]['id_conf']);
    /* add topics */
    $full_arr[$index]['topics'] = getTopicsByConf($full_arr[$index]['id_conf']);
    /*foreach conference add editions foreach edition add location and activities */
    foreach ($editions = getEditions(
        $full_arr[$index]['id_conf']) as $edition_index => $edition) {
        /* add edition's location name and url */
        $edition['location']['location_name'] = getLocationsByConf(
            $full_arr[$index]['id_conf'], $edition['id_edition'])[0]['name_location'];
        $edition['location']['location_url'] = getLocationsByConf(
            $full_arr[$index]['id_conf'], $edition['id_edition'])[0]['url_location'];
        /* for each activity add key date and activity's name */
        foreach ($activities = getActivitiesByConf(
            $full_arr[$index]['id_conf'],
            $edition['id_edition']) as $activity_index => $activity) {
                $activity['activity_name'] = $activities[$activity_index]['activity_name'];
                $activity['key_date'] = $activities[$activity_index]['key_date'];
                $activities[$activity_index] = $activity;
                $edition['activities'] = $activities;
            }
        /* adding edition row to editions array */
        $editions[$edition_index] = $edition;
    }
    /*adding editions array to conference row*/
    $full_arr[$index]['editions'] = $editions;
}
/* encoding json array */
$full_arr = json_encode($full_arr);
print_r($full_arr);

/*$full_arr = json_encode($full_arr,JSON_PRETTY_PRINT);
echo '<pre>'.$full_arr.'</pre>';*/
