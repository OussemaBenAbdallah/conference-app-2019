package edu.oussemabenabdallah.pfa.conferenceapp;

import android.os.Parcel;
import android.os.Parcelable;

public class Location implements Parcelable {
    String locationName;
    String locationUrl;

    public Location(String locationName, String locationUrl) {
        this.locationName = locationName;
        this.locationUrl = locationUrl;
    }

    public Location(String locationName) {
        this.locationName = locationName;
    }

    protected Location(Parcel in) {
        locationName = in.readString();
        locationUrl = in.readString();
    }

    public static final Creator<Location> CREATOR = new Creator<Location>() {
        @Override
        public Location createFromParcel(Parcel in) {
            return new Location(in);
        }

        @Override
        public Location[] newArray(int size) {
            return new Location[size];
        }
    };

    @Override
    public int describeContents() {
        return this.hashCode();
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(locationName);
        dest.writeString(locationUrl);
    }
}
