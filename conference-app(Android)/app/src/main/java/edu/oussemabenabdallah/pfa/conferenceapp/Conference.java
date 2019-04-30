package edu.oussemabenabdallah.pfa.conferenceapp;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.ArrayList;
import java.util.List;

public class Conference implements Parcelable {
    int id;
    String fullName, shortName, website, description,email;
    Admin admin;
    double colorIndex;
    ArrayList<String> topics, speakers;
    ArrayList<Edition> editions;

    public Conference(int id, String fullName, String shortName, Admin admin) {
        this.id = id;
        this.fullName = fullName;
        this.shortName = shortName;
        this.admin = admin;
        this.email = "";
        this.website = "";
        this.description = "";
        this.editions = new ArrayList<>();
        this.topics = new ArrayList<>();
        this.speakers = new ArrayList<>();
        int s = 0;
        for (int i = 0; i < this.shortName.length(); i++)
            s += (int) this.shortName.charAt(i);
        this.colorIndex = s / Math.pow(10, String.valueOf(s).length());
    }

    protected Conference(Parcel in) {
        id = in.readInt();
        fullName = in.readString();
        shortName = in.readString();
        admin = in.readParcelable(Admin.class.getClassLoader());
        email = in.readString();
        website = in.readString();
        description = in.readString();
        colorIndex = in.readDouble();
        topics = in.createStringArrayList();
        speakers = in.createStringArrayList();
        editions = in.createTypedArrayList(Edition.CREATOR);
    }

    @Override
    public int describeContents() {
        return this.hashCode();
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeInt(id);
        dest.writeString(fullName);
        dest.writeString(shortName);
        dest.writeParcelable(admin,flags);
        dest.writeString(email);
        dest.writeString(website);
        dest.writeString(description);
        dest.writeDouble(colorIndex);
        dest.writeStringList(topics);
        dest.writeStringList(speakers);
        dest.writeTypedList(editions);

    }

    public static final Creator<Conference> CREATOR = new Creator<Conference>() {
        @Override
        public Conference createFromParcel(Parcel in) {
            return new Conference(in);
        }

        @Override
        public Conference[] newArray(int size) {
            return new Conference[size];
        }
    };

}
