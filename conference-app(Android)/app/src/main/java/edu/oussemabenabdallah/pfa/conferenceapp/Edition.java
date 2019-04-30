package edu.oussemabenabdallah.pfa.conferenceapp;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.ArrayList;

public class Edition implements Parcelable {
    int id;
    Location location;
    ArrayList<KeyDate> keyDates;

    public Edition(int id, Location location) {
        this.id = id;
        this.location = location;
        this.keyDates = new ArrayList<KeyDate>();
    }


    protected Edition(Parcel in) {
        id = in.readInt();
        location = in.readParcelable(Location.class.getClassLoader());
        keyDates =  in.createTypedArrayList(KeyDate.CREATOR);
    }


    @Override
    public int describeContents() {
        return this.hashCode();
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeInt(id);
        dest.writeParcelable(location,flags);
        dest.writeTypedList(keyDates);
    }


    public static final Creator<Edition> CREATOR = new Creator<Edition>() {
        @Override
        public Edition createFromParcel(Parcel in) {
            return new Edition(in);
        }

        @Override
        public Edition[] newArray(int size) {
            return new Edition[size];
        }
    };

}
