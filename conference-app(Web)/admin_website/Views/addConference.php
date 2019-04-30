<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script>
        let topicNumber = 1;
        let keyDateNumber = 1;
        let speakerNumber = 1;

        function addTopic(value = '') {
            topicNumber++;
            let topics = document.getElementById('topics');
            let topicNode = document.createElement('input');
            let labelNode = document.createElement('label');
            labelNode.setAttribute('class', 'col-lg-4 col-form-label form-control-label');
            topicNode.setAttribute('class', 'form-control col-lg-7 my-2');
            topicNode.setAttribute('type', 'text');
            topicNode.setAttribute('name', 'topic' + topicNumber);
            topicNode.setAttribute('id', 'topic' + topicNumber);
            topicNode.setAttribute('value', value);
            topics.appendChild(labelNode);
            topics.appendChild(topicNode)
        }

        function addKeyDate(activity_name = '', key_date = '') {
            keyDateNumber++;
            let keyDates = document.getElementById('keyDates');
            let activityNode = document.createElement('input');
            let dateNode = document.createElement('input');
            let activityClass = 'form-control col-lg-4 mx-1 my-2';
            let activityId = 'activity' + keyDateNumber;
            let dateId = 'date' + keyDateNumber;
            let labelNode = document.createElement('label');
            labelNode.setAttribute('class', 'col-lg-4 col-form-label form-control-label');

            activityNode.setAttribute('class', activityClass);
            activityNode.setAttribute('type', 'text');
            activityNode.setAttribute('name', activityId);
            activityNode.setAttribute('id', activityId);
            activityNode.setAttribute('value', activity_name);

            dateNode.setAttribute('class', 'form-control col-lg-3 my-2 mx-1');
            dateNode.setAttribute('type', 'datetime-local');
            dateNode.setAttribute('name', dateId);
            dateNode.setAttribute('id', dateId);
            dateNode.setAttribute('value', key_date);

            keyDates.appendChild(labelNode);
            keyDates.appendChild(activityNode);
            keyDates.appendChild(dateNode);

        }

        function addSpeaker(value) {
            speakerNumber++;
            let speakers = document.getElementById('speakers');
            let labelNode = document.createElement('label');
            labelNode.setAttribute('class', 'col-lg-4 col-form-label form-control-label');
            let speakerNode = document.createElement('input');
            speakerNode.setAttribute('class', 'form-control col-lg-7 my-2');
            speakerNode.setAttribute('type', 'input');
            speakerNode.setAttribute('name', 'speaker' + speakerNumber);
            speakerNode.setAttribute('id', 'speaker' + speakerNumber);
            speakerNode.setAttribute('value', value);

            speakers.appendChild(labelNode);
            speakers.appendChild(speakerNode);
        }


    </script>
    <title>Add conference</title>


</head>

<body class="container py-3">

<article class="row">
    <section class="mx-auto col-sm-10">
        <div class="card" id="conferenceCard">
            <!-- form header -->
            <div class="card-header">
                <h4 class="mb-0">Conference Information</h4>
            </div>
            <!-- form body -->
            <div class="card-body">
                <form class="form" action="../API/newConference.php" method="get" id="form">
                    <input type="hidden"
                           value="<?php echo !empty($_GET['email_admin']) ? htmlspecialchars($_GET['email_admin']) : ''; ?>"
                           name="email_admin">
                    <input type="hidden"
                           value="<?php echo !empty($_GET['id_conf']) ? htmlspecialchars($_GET['id_conf']) : ''; ?>"
                           name="id_conf" id="id_conf">

                    <div class="form-group row mx-1">
                        <label class="col-lg-4 col-form-label form-control-label">Full name</label>
                        <input class="form-control col-lg-8" type="text" name="full_name_conf" id="full_name_conf"
                               placeholder="International Conference on Information and Communication Technology and Accessibility"
                               value="<?php echo !empty($_GET['full_name_conf']) ? htmlspecialchars($_GET['full_name_conf']) : ''; ?>">
                    </div>
                    <div class="form-group row mx-1">
                        <label class="col-lg-4 col-form-label form-control-label">Short name</label>
                        <input class="form-control col-lg-8" type="text" name="short_name_conf" id="short_name_conf"
                               placeholder="ICTA"
                               value="<?php echo !empty($_GET['short_name_conf']) ? htmlspecialchars($_GET['short_name_conf']) : ''; ?>">
                    </div>
                    <div class="form-group row mx-1">
                        <label class="col-lg-4 col-form-label form-control-label">Website</label>
                        <input class="form-control col-lg-8" type="url" name="website" id="website"
                               placeholder="www.icta.rnu.tn"
                               value="<?php echo !empty($_GET['website']) ? htmlspecialchars($_GET['website']) : ''; ?>">
                    </div>
                    <div class="form-group row mx-1">
                        <label class="col-lg-4 col-form-label form-control-label">Email</label>
                        <input class="form-control col-lg-8" type="email" name="email_conf" id="email_conf"
                               placeholder="icta@icta.rnu.tn"
                               value="<?php echo !empty($_GET['email_conf']) ? htmlspecialchars($_GET['email_conf']) : ''; ?>">
                    </div>
                    <div class="form-group row mx-1">
                        <label for="description" class="col-lg-4 col-form-label form-control-label">Short
                            description</label>
                        <textarea class="form-control col-lg-8" type="text" name="description"
                                  id="description"><?php echo !empty($_GET['description']) ? htmlspecialchars($_GET['description']) : ''; ?>
                        </textarea>
                    </div>
                    <div class="form-group row p-1 border mx-1" id="speakers">
                        <label class="col-lg-4 col-form-label form-control-label">Speakers :</label>
                        <input class="form-control col-lg-7" type="text" name="speaker1" id="speaker1"
                               placeholder="Speaker full name"
                               value="<?php echo !empty($_GET['speaker1']) ? htmlspecialchars($_GET['speaker1']) : ''; ?>"/>
                        <button type="button" class="btn btn-success mx-3" onclick="addSpeaker('')">+</button>
                    </div>
                    <div class="form-group row p-1 border mx-1" id="topics">
                        <label class="col-lg-4 col-form-label form-control-label">Topics:</label>
                        <input class="form-control col-lg-7" type="text" name="topic1" id="topic1"
                               placeholder="E-Accessibility"
                               value="<?php echo !empty($_GET['topic1']) ? htmlspecialchars($_GET['topic1']) : ''; ?>"/>
                        <button type="button" class="btn btn-success mx-3" onclick="addTopic()">+</button>
                    </div>
                    <div class="card" id="editionCard">
                        <div class="card-header">
                            <h4 class="mb-0">Conference New Edition Information</h4>
                        </div>
                        <!-- form body -->
                        <div class="card-body ">

                            <div class="form-group row">
                                <label for="id_edition" class="col-lg-4 col-form-label form-control-label">Edition
                                    number</label>
                                <input class="form-control col-lg-2" type="number" name="id_edition" id="id_edition"
                                       min="1"
                                       value="<?php echo !empty($_GET['id_edition']) ? htmlspecialchars($_GET['id_edition']) : '1'; ?>"/>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label form-control-label">Location</label>
                                <input class="form-control col-lg-3 mx-1 my-2" type="text" name="name_location"
                                       id="name_location"
                                       placeholder="Hammamet, Tunisia"
                                       value="<?php echo !empty($_GET['name_location']) ? htmlspecialchars($_GET['name_location']) : ''; ?>"/>
                                <input class="form-control col-lg-4 mx-1 my-2" type="text" name="url_location"
                                       id="url_location"
                                       placeholder="Location's URL"
                                       value="<?php echo !empty($_GET['url_location']) ? htmlspecialchars($_GET['url_location']) : ''; ?>">
                            </div>

                            <div class="form-group row border  p-1" id="keyDates">
                                <label class="col-lg-4 col-form-label form-control-label">Key Dates</label>

                                <input class="form-control col-lg-4 mx-1 my-2"
                                       type="text" name="activity1"
                                   id="activity1" placeholder="Papers submission"
                                   value=
                       "<?php echo !empty($_GET['activity1']) ? htmlspecialchars($_GET['activity1']) : ''; ?>"/>

                                <input class="form-control col-lg-3 my-2 mx-1"
                                       type="datetime-local" name="date1" id="date1"
                                        value=
                       "<?php echo !empty($_GET['date1']) ? htmlspecialchars($_GET['date1']) : ''; ?>"/>
                                <button type="button" class="btn btn-success my-2 mx-2" onclick="addKeyDate()">+
                                </button>

                            </div>
                        </div>

                    </div>
                    <div class="ml-5 mt-5 buttons">
                        <input class="btn btn-secondary w-25 ml-2 mr-1" type="reset" value="Cancel">
                        <input type="button" class="btn btn-success w-25 ml-2 mr-1" value="New edition" id="newEdition">
                        <input type="submit" class="btn btn-primary w-25 ml-1" value="Save Changes">
                    </div>
                </form>
            </div>
        </div>
        <!-- /form user info -->
    </section>
</article>
</body>
<script>

    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let conferences = JSON.parse(this.responseText);
            initialize(document.getElementById('id_conf').value, conferences);
        }
    };
    xhr.open("GET", "../API/conferences.php", true);
    xhr.send();

    let initialize = (id_conf, conferences) => {
        let conferenceIndex = conferences.findIndex((conference) => {
            return conference.id_conf === id_conf;
        });
        let editions = conferences[conferenceIndex].editions;
        let speakers = conferences[conferenceIndex].speakers;
        let topics = conferences[conferenceIndex].topics;
        let activities = editions[editions.length - 1].activities;

        speakers.forEach((speaker, index) => {
            if (index > 0)
                addSpeaker(speaker);
        });

        topics.forEach((topic, index) => {
            if (index > 0)
                addTopic(topic);
        });


        activities.forEach((activity, index) => {
            if (index > 0)
                addKeyDate(activity.activity_name, activity.key_date)
        });
    };

    $('#newEdition').click(() => {
        let lastEdition = parseInt($('#id_edition').val());
        $('#editionCard').find('input').not('button', 'submit', 'hidden', 'reset').val('');
        $('#id_edition').val(lastEdition + 1);
    });


</script>
</html>