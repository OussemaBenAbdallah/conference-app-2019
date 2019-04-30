<?php

include_once '../db/database.php';

$database = new Database();
$db = $database->getConnection();

function getTopicsByConf($id_conf)
{
    global $db;
    $topics = array();
    $query = "SELECT topics.TOPIC_NAME from topics,conf_topic where conf_topic.ID_CONF=" . $id_conf
        . " and topics.ID_TOPIC=conf_topic.ID_TOPIC";

    $stmt = $db->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($topics, $row['TOPIC_NAME']);

    }
    return $topics;
}

function getSpeakersByConf($id_conf)
{
    global $db;
    $speakers = array();
    $query = "select FULL_NAME_SPEAKER from speakers,conf_speaker where conf_speaker.ID_CONF=" . $id_conf
        . " and speakers.ID_SPEAKER=conf_speaker.ID_SPEAKER";

    $stmt = $db->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($speakers, $row['FULL_NAME_SPEAKER']);
    }
    return $speakers;
}

function getLocationsByConf($id_conf, $id_edition)
{
    global $db;
    $locations = array();
    $query = "select distinct NAME_LOCATION,URL_LOCATION from locations,conf_date_loc where conf_date_loc.ID_CONF=" . $id_conf
        . " and conf_date_loc.ID_EDITION=" . $id_edition . " and locations.ID_LOCATION=conf_date_loc.ID_LOCATION";

    $stmt = $db->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($locations, [
            'name_location' => $row['NAME_LOCATION'],
            'url_location' => $row['URL_LOCATION']
        ]);

    }
    return $locations;
}

function getActivitiesByConf($id_conf, $id_edition)
{
    global $db;
    $activities = array();
    $query = "select ACTIVITY_NAME,KEY_DATE from activities,conf_date_loc where conf_date_loc.ID_CONF=" . $id_conf
        . " and conf_date_loc.ID_EDITION=" . $id_edition . " and activities.ID_ACTIVITY=conf_date_loc.ID_ACTIVITY";

    $stmt = $db->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($activities, [
            'activity_name' => $row['ACTIVITY_NAME'],
            'key_date' => $row['KEY_DATE']
        ]);

    }
    return $activities;
}

function getEditions($id_conf)
{
    global $db;
    $editions = array();
    $query = "SELECT distinct ID_EDITION  from conf_date_loc where ID_CONF=" . $id_conf;
    $stmt = $db->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($editions,[
            "id_edition"=> $row['ID_EDITION']
        ]);
    }
    return $editions;
}

