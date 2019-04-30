package edu.oussemabenabdallah.pfa.conferenceapp;

import android.os.Parcel;
import android.os.Parcelable;

public class KeyDate implements Parcelable {
    String activity;
    String date;

    public KeyDate(String activity, String date) {
        this.activity = activity;
        this.date = date;
    }

    protected KeyDate(Parcel in) {
        activity = in.readString();
        date = in.readString();
    }

    public static final Creator<KeyDate> CREATOR = new Creator<KeyDate>() {
        @Override
        public KeyDate createFromParcel(Parcel in) {
            return new KeyDate(in);
        }

        @Override
        public KeyDate[] newArray(int size) {
            return new KeyDate[size];
        }
    };

    @Override
    public int describeContents() {
        return this.hashCode();
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(activity);
        dest.writeString(date);
    }
}
