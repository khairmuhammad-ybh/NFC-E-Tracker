package com.weebly.hackd.nfctracker;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.Html;
import android.widget.TextView;

public class About extends AppCompatActivity {

    private TextView developer,versionNo;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_about);

        String versionName = BuildConfig.VERSION_NAME;
        String develop = "Developer: <b><i>HACKD PRODUCTION</i></b>";
        String verNo = "Version: <i>"+ versionName + "</i>";
        developer = (TextView)findViewById(R.id.developer);
        versionNo = (TextView)findViewById(R.id.versionNo);

        developer.setText(Html.fromHtml(develop));
        versionNo.setText(Html.fromHtml(verNo));

    }
}
