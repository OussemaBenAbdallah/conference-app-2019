package edu.oussemabenabdallah.pfa.conferenceapp;

import android.app.IntentService;
import android.app.PendingIntent;
import android.app.TaskStackBuilder;
import android.content.Intent;
import android.media.RingtoneManager;
import android.net.Uri;
import android.support.v4.app.NotificationCompat;
import android.support.v4.app.NotificationManagerCompat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;


public class BackgroundNotificationService extends IntentService {
    private static final String TAG = BackgroundNotificationService.class.getSimpleName();
    NotificationCompat.Builder builder;

    NotificationManagerCompat notificationManager;

    public BackgroundNotificationService() {
        super(TAG);
    }

    @Override
    protected void onHandleIntent(Intent intent) {
        blockingTask();
    }

    void blockingTask() {
        int next = 5000;
        Uri alarmSound = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
        builder = new NotificationCompat.Builder(this, MyChannels.CHANNEL_ID_1)
                .setSmallIcon(R.drawable.newconference)
                .setContentTitle("New Conference")
                .setContentText("Much longer text that cannot fit one line...")
                .setStyle(new NotificationCompat.BigTextStyle()
                        .bigText("Much longer text that cannot fit one line..."))
                .setPriority(NotificationCompat.PRIORITY_HIGH)
                .setCategory(NotificationCompat.CATEGORY_MESSAGE);
        notificationManager = NotificationManagerCompat.from(this);
        Date currentTime = Calendar.getInstance().getTime();
        ArrayList<Conference> oldList = null;

        while (true) {
            if (Calendar.getInstance().getTime().getTime() == currentTime.getTime() + next) {
                next += 5000;
                int newConference;
                MainActivity.FetchJson fetchJson  = new MainActivity.FetchJson();
                try {
                    ArrayList<Conference> conferences = fetchJson.execute().get();
                    System.out.println("conferences: " + conferences.size());
                    if(oldList != null)
                        for (Conference conference: conferences) {
                            newConference = 1;
                            for (Conference oldConference: oldList) {
                                if(conference.id == oldConference.id) {
                                    newConference = 0;
                                    break;
                                }
                            }
                            if(newConference == 1) {
                                Intent resultIntent = new Intent(this, ConferenceDetails.class);
                                resultIntent.putExtra("conference",conference);
                                TaskStackBuilder stackBuilder = TaskStackBuilder.create(this);
                                stackBuilder.addNextIntentWithParentStack(resultIntent);
                                PendingIntent resultPendingIntent = stackBuilder.getPendingIntent(0,PendingIntent.FLAG_UPDATE_CURRENT);
                                builder.setContentTitle("new conference " + conference.shortName);
                                builder.setContentText(conference.fullName + ": " + conference.description).setContentIntent(resultPendingIntent);
                                notificationManager.notify(1, builder.build());
                            }
                        }
                        oldList = conferences;


                } catch (Exception e) {
                    builder.setContentTitle("faild");
                }
            }
        }


    }
}
