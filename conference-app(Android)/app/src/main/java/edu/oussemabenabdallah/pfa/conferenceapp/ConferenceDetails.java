package edu.oussemabenabdallah.pfa.conferenceapp;

import android.content.Intent;
import android.provider.CalendarContract;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.time.LocalDateTime;
import java.util.Date;

public class ConferenceDetails extends AppCompatActivity {
    TextView fullName, topics, description, keydates, location, locationUrl, speakers,website,email;
    ImageView addToCalendar;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_conference_details);
        fullName = findViewById(R.id.fullName);
        topics = findViewById(R.id.topics);
        description = findViewById(R.id.description);
        keydates = findViewById(R.id.keydates);
        location = findViewById(R.id.location);
        locationUrl = findViewById(R.id.locationUrl);
        speakers = findViewById(R.id.speakers);
        website = findViewById(R.id.website);
        email = findViewById(R.id.email);
        addToCalendar = findViewById(R.id.addToCalender);
        String topicsContent = "", keydatesContent = "", speakersContent = "";


        Intent intent = getIntent();
        Conference conference = intent.getParcelableExtra("conference");
        Edition nextEdtion = conference.editions.get(conference.editions.size() - 1);
        if (conference.fullName.length() > 20) {
            fullName.setTextSize(20);
        }
        fullName.setText(conference.fullName);
        description.setText(conference.description);
        location.setText(nextEdtion.location.locationName);
        if (!nextEdtion.location.locationUrl.equals("")){
            locationUrl.setText(nextEdtion.location.locationUrl);

        }


        for (String topic : conference.topics) {
            topicsContent += topic;
            if (topic == conference.topics.get(conference.topics.size() - 1))
                break;
            topicsContent += ", ";
        }
        topics.setText(topicsContent);

        for (KeyDate keyDate : nextEdtion.keyDates) {
            keydatesContent += "\u2022 " + keyDate.activity + "\n on " + keyDate.date;
            if (nextEdtion.keyDates.indexOf(keyDate) < nextEdtion.keyDates.size() - 1)
                keydatesContent += "\n\n";
        }
        keydates.setText(keydatesContent);
        for (String speaker : conference.speakers) {
            speakersContent += "\u2022 " + speaker;
            if (conference.speakers.indexOf(speaker) < conference.speakers.size() - 1)
                speakersContent += "\n\n";
        }

        website.setText(conference.website);
        email.setText(conference.email);

        speakers.setText(speakersContent);
        int color = MainActivity.getColor(conference.colorIndex);

        fullName.setTextColor(color);
        topics.setTextColor(color);
        locationUrl.setLinkTextColor(color);
        website.setLinkTextColor(color);
        email.setLinkTextColor(color);


        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm");
        try {
            final Date date = format.parse(nextEdtion.keyDates.get(0).date);
            final String title = conference.fullName + "-" + nextEdtion.keyDates.get(0).activity;
            final String location  = nextEdtion.location.locationName;

            addToCalendar.setClickable(true);
            addToCalendar.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Intent intent  = new Intent(Intent.ACTION_EDIT);
                    intent.setType("vnd.android.cursor.item/event");
                    intent.putExtra("beginTime",date.getTime());
                    intent.putExtra("endTime",date.getTime() + 3600 * 1000);
                    intent.putExtra("title",title);
                    intent.putExtra(CalendarContract.Events.EVENT_LOCATION, location);
                    startActivity(intent);
                }
            });

        } catch (ParseException e) {
            Toast.makeText(ConferenceDetails.this, "wrong data type",Toast.LENGTH_SHORT).show();
        }
    }
}
