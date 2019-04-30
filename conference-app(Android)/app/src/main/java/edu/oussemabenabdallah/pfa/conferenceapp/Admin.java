package edu.oussemabenabdallah.pfa.conferenceapp;

import android.os.Parcel;
import android.os.Parcelable;

public class Admin implements Parcelable {
    String email;
    String name;

    public Admin(String email, String name) {
        this.email = email;
        this.name = name;
    }

    protected Admin(Parcel in) {
        email = in.readString();
        name = in.readString();
    }

    public static final Creator<Admin> CREATOR = new Creator<Admin>() {
        @Override
        public Admin createFromParcel(Parcel in) {
            return new Admin(in);
        }

        @Override
        public Admin[] newArray(int size) {
            return new Admin[size];
        }
    };

    @Override
    public int describeContents() {
        return this.hashCode();
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(email);
        dest.writeString(name);
    }
}