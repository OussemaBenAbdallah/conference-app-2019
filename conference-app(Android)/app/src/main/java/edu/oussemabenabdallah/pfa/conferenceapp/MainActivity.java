package edu.oussemabenabdallah.pfa.conferenceapp;


import android.content.Context;
import android.content.Intent;
import android.content.res.TypedArray;
import android.graphics.Color;
import android.graphics.PorterDuff;
import android.graphics.drawable.Drawable;
import android.os.AsyncTask;
import android.support.annotation.NonNull;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.SearchView;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.ExecutionException;

public class MainActivity extends AppCompatActivity implements ItemClickListener {
    private ArrayList<Conference> conferences = new ArrayList<>();
    private static TypedArray colors;
    private SearchView searchView;
    private static Adapter adapter;
    private RecyclerView recyclerView;
    private Button refresh;

    /* get random color from typed array*/
    public static int getColor(double colorIndex) {
        int index = (int) (colorIndex * colors.length());
        return colors.getColor(index, Color.BLACK);
    }

    /* refresh conference lis method */
    public void onRefresh() {

        FetchJson fetchJson = new FetchJson();
        try {
            ArrayList<Conference> list = fetchJson.execute().get();
            if (list != null) {
                conferences = list;
                recyclerView.setLayoutManager(new LinearLayoutManager(MainActivity.this));
                Adapter refreshAdapter = new Adapter(MainActivity.this, conferences);
                refreshAdapter.setItemClickListener(MainActivity.this);
                recyclerView.setAdapter(refreshAdapter);

            } else {
                Toast.makeText(MainActivity.this, "database unavailable", Toast.LENGTH_LONG).show();
            }
        } catch (ExecutionException e) {
            Toast.makeText(MainActivity.this, "can't fetch data", Toast.LENGTH_LONG).show();
        } catch (InterruptedException e) {
            Toast.makeText(MainActivity.this, "Process interrupted", Toast.LENGTH_LONG).show();
        }


    }

    /* Search conferences by topics method */

    private ArrayList<Conference> searchConferencesByTopic(ArrayList<Conference> conferences, String topic) {
        ArrayList<Conference> searchConferences = new ArrayList<>();
        for (Conference conference : conferences) {
            for (String queryTopic : conference.topics) {
                if (queryTopic.equalsIgnoreCase(topic)) {
                    searchConferences.add(conference);
                    break;
                }
            }

        }
        return searchConferences;
    }

/* on create main activity method */

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        refresh = findViewById(R.id.refresh);
        colors = getResources().obtainTypedArray(getResources().getIdentifier("colors",
                "array", getApplicationContext().getPackageName()));
        try {
            conferences = new FetchJson().execute().get();
            if (conferences == null) {
                Toast.makeText(this, "database unavailable", Toast.LENGTH_LONG).show();
            }

            recyclerView = findViewById(R.id.conferenceList);
            recyclerView.setLayoutManager(new LinearLayoutManager(this));
            adapter = new Adapter(this, this.conferences);
            adapter.setItemClickListener(this);
            recyclerView.setAdapter(adapter);
            searchView = findViewById(R.id.search);

            /* apply search method to the search view*/
            searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
                ArrayList<Conference> oldConferences = conferences;

                /* standard search method */

                @Override
                public boolean onQueryTextSubmit(String query) {
                    conferences = searchConferencesByTopic(oldConferences, query);
                    if (conferences.size() == 0) {
                        Toast.makeText(MainActivity.this, "topic not found", Toast.LENGTH_LONG).show();
                    } else {
                        recyclerView.setLayoutManager(new LinearLayoutManager(MainActivity.this));
                        Adapter searchAdapter = new Adapter(MainActivity.this, conferences);
                        searchAdapter.setItemClickListener(MainActivity.this);
                        recyclerView.setAdapter(searchAdapter);
                    }
                    return false;
                }

                /* live search method */

                @Override
                public boolean onQueryTextChange(String newText) {
                    if (newText.length() == 0) {
                        onRefresh();
                    } else {
                        conferences = searchConferencesByTopic(oldConferences, newText);
                        recyclerView.setLayoutManager(new LinearLayoutManager(MainActivity.this));
                        adapter = new Adapter(MainActivity.this, conferences);
                        adapter.setItemClickListener(MainActivity.this);
                        recyclerView.setAdapter(adapter);
                    }
                    return false;
                }
            });

            /* refresh method on the refresh button click */
            refresh.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    onRefresh();
                }
            });

        } catch (ExecutionException e) {
            Toast.makeText(MainActivity.this, "can't fetch data", Toast.LENGTH_LONG).show();
        } catch (InterruptedException e) {
            Toast.makeText(MainActivity.this, "Process interrupted", Toast.LENGTH_LONG).show();
        }

        Intent intentBackground = new Intent(this, BackgroundNotificationService.class);
        startService(intentBackground);

    }

    /* overriding onItemClick method since MainActivity extends ItemClickListener*/

    @Override
    public void OnItemClick(View view, int position) {
        Conference conference = conferences.get(position);
        Intent intent = new Intent(MainActivity.this, ConferenceDetails.class);
        intent.putExtra("conference", conference);
        startActivity(intent);
    }

    /* class of the recycler view adapter */

    public class Adapter extends RecyclerView.Adapter<Adapter.Holder> {
        private List<Conference> conferences;
        private LayoutInflater inflater;
        private ItemClickListener itemClickListener;


        Adapter(Context context, List<Conference> conferences) {
            this.inflater = LayoutInflater.from(context);
            this.conferences = conferences;
        }

        public void clear() {
            conferences.clear();
            notifyDataSetChanged();
        }

        public void addAll(List<Conference> conferences) {
            this.conferences.addAll(conferences);
            notifyDataSetChanged();
        }

        @NonNull
        @Override
        public Holder onCreateViewHolder(@NonNull ViewGroup parent, int position) {
            View view = this.inflater.inflate(R.layout.conference_row, parent, false);
            return new Holder(view);
        }

        @Override
        public void onBindViewHolder(@NonNull Holder holder, int position) {
            Conference conference = this.conferences.get(position);
            holder.shortName.setText(conference.shortName);
            if (conference.shortName.length() > 3) {
                holder.shortName.setTextSize(16);
            }
            if (conference.fullName.length() > 30)
                holder.fullName.setText(conference.fullName.substring(0, 30) + "...");
            else
                holder.fullName.setText(conference.fullName);
            Edition lastEdition = conference.editions.get(conference.editions.size() - 1);
            holder.date.setText(lastEdition.keyDates.get(0).date);
            if (lastEdition.location.locationName.length() > 20) {
                holder.location.setTextSize(10);
            }
            holder.location.setText(lastEdition.location.locationName);
            holder.admin.setText(conference.admin.name);
            Drawable circle = holder.shortName.getBackground();
            circle.setColorFilter(getColor(conference.colorIndex), PorterDuff.Mode.SRC_ATOP);

        }

        @Override
        public int getItemCount() {
            if (conferences != null)
                return this.conferences.size();
            else return 0;
        }


        /* class of the adapter holder */

        public class Holder extends RecyclerView.ViewHolder implements View.OnClickListener {
            TextView shortName, fullName, date, location, admin;

            Holder(View itemView) {
                super(itemView);
                shortName = itemView.findViewById(R.id.shortName);
                fullName = itemView.findViewById(R.id.fullName);
                date = itemView.findViewById(R.id.date);
                location = itemView.findViewById(R.id.location);
                admin = itemView.findViewById(R.id.admin);
                itemView.setOnClickListener(this);

            }


            @Override
            public void onClick(View view) {
                if (itemClickListener != null)
                    itemClickListener.OnItemClick(view, getAdapterPosition());
            }
        }

        Conference getConference(int id) {
            return conferences.get(id);
        }

        void setItemClickListener(ItemClickListener itemClickListener) {
            this.itemClickListener = itemClickListener;
        }
    }

    /* class FetchJson responsible for returning the jsonArray of conferences from server */

    public static class FetchJson extends AsyncTask<Void, Void, ArrayList<Conference>> {
        private String data = "";
        ArrayList<Conference> fetchData(String jsonData) throws JSONException {
            ArrayList<Conference> conferences = new ArrayList<>();
            JSONArray jsonConferences = new JSONArray(jsonData);
            for (int i = 0; i < jsonConferences.length(); i++) {
                JSONObject jsonConference = jsonConferences.getJSONObject(i);
                int conferenceID = jsonConference.getInt("id_conf");
                String conferenceFullName = jsonConference.getString("full_name_conf");
                String conferenceShortName = jsonConference.getString("short_name_conf");
                String conferenceWebsite = jsonConference.getString("website");
                String conferenceEmail = jsonConference.getString("email_conf");
                String conferenceDescription = jsonConference.getString("description");
                ArrayList<String> topics = new ArrayList<>();
                ArrayList<String> speakers = new ArrayList<>();
                ArrayList<Edition> editions = new ArrayList<>();
                JSONObject conferenceAdmin = jsonConference.getJSONObject("admin");
                String conferenceAdminEmail = conferenceAdmin.getString("email_admin");
                String conferenceAdminName = conferenceAdmin.getString("name_admin");
                Admin admin = new Admin(conferenceAdminEmail, conferenceAdminName);

                JSONArray jsonTopics = jsonConference.getJSONArray("topics");
                for (int j = 0; j < jsonTopics.length(); j++) {
                    topics.add(jsonTopics.get(j).toString());
                }
                JSONArray jsonSpeakers = jsonConference.getJSONArray("speakers");
                for (int j = 0; j < jsonSpeakers.length(); j++) {
                    speakers.add(jsonSpeakers.get(j).toString());
                }
                JSONArray jsonEditions = jsonConference.getJSONArray("editions");
                for (int j = 0; j < jsonEditions.length(); j++) {
                    JSONObject jsonEdition = jsonEditions.getJSONObject(j);
                    int editionID = jsonEdition.getInt("id_edition");
                    ArrayList<KeyDate> keyDates = new ArrayList<>();
                    JSONObject jsonLocation = jsonEdition.getJSONObject("location");
                    Location location = new Location(jsonLocation.getString("location_name"),
                            jsonLocation.getString("location_url"));
                    JSONArray jsonKeyDates = jsonEdition.getJSONArray("activities");
                    for (int k = 0; k < jsonKeyDates.length(); k++) {
                        keyDates.add(new KeyDate(((JSONObject) jsonKeyDates.get(k)).getString("activity_name"),
                                ((JSONObject) jsonKeyDates.get(k)).getString("key_date")));
                    }
                    Edition edition = new Edition(editionID, location);
                    edition.keyDates = keyDates;
                    editions.add(edition);
                }

                Conference conference = new Conference(
                        conferenceID, conferenceFullName, conferenceShortName, admin);
                conference.website = conferenceWebsite;
                conference.email = conferenceEmail;
                conference.description = conferenceDescription;
                conference.topics = topics;
                conference.speakers = speakers;
                conference.editions = editions;
                conferences.add(conference);
            }
            return conferences;
        }
        @Override
        protected ArrayList<Conference> doInBackground(Void... voids) {
            try { URL url = new URL(
                        "http://192.168.1.15/pfa2/admin_website/API/conferences.php");
                HttpURLConnection httpURLConnection = (HttpURLConnection) url.openConnection();
                BufferedReader bufferedReader = new BufferedReader(
                        new InputStreamReader(httpURLConnection.getInputStream()));
                String line = "";
                while (line != null) {
                    line = bufferedReader.readLine();
                    data += line;
                }
                return fetchData(data);
            } catch (Exception e) {
                e.printStackTrace();
            }
            return null;
        }
        @Override
        protected void onPostExecute(ArrayList<Conference> resultConferences) {
            super.onPostExecute(resultConferences);
            adapter.notifyDataSetChanged();
        }
    }




























}

/* custom interface that MainActivity should implement to add the onItemClickListener to the recycler view*/

interface ItemClickListener {
    void OnItemClick(View view, int position);
}
