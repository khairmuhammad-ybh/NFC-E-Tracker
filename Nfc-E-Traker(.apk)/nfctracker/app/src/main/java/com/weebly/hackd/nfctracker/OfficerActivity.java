package com.weebly.hackd.nfctracker;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.provider.Settings.Secure;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import java.util.HashMap;
import java.util.Map;


public class OfficerActivity extends AppCompatActivity {

    private TextView currentUser;

    //Auto Update (Auto-Update-Apk)
    private AutoUpdateApk aua;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_officer);

        aua = new AutoUpdateApk(getApplicationContext());

        mapUserToPhone();
        currentUser = (TextView)findViewById(R.id.etView);
        //Fetching data from shared preferences
        SharedPreferences sharedPreferences = getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
        String user = sharedPreferences.getString(Config.KEY_NAME,"Not Available");
        currentUser.setText("Welcome " + user);


    }

    //Link to url
    public void RecordView(View View){
        Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(Config.WEB_LOGIN));
        startActivity(browserIntent);
    }

    //map user account with android id
    private void mapUserToPhone() {

        String aID = Secure.getString(getContentResolver(), Secure.ANDROID_ID);

        SharedPreferences preferences = getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
        //Fetching values form sharedpreferences
        final String company = preferences.getString(Config.KEY_COMPANY,"null");        //Company
        final String name = preferences.getString(Config.KEY_NAME, "null");             //Username
        final String pass = preferences.getString(Config.KEY_PASS, "null");         //Password
        final String androidid = aID;                                                   //Android_ID

        //Creating a string request
        StringRequest stringRequest = new StringRequest(Request.Method.POST, Config.USER_VERIFY,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        if (response.equalsIgnoreCase("first_access")) {
                            SharedPreferences sharedPreferences = OfficerActivity.this.getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
                            //Creating editor to store values to shared preferences
                            SharedPreferences.Editor editor = sharedPreferences.edit();
                            //Adding values to editor
                            editor.putBoolean(Config.KEY_VERIFIED, true);
                            //Saving values to editor
                            editor.commit();
                            //Alert
                            Toast.makeText(OfficerActivity.this, "User account successfully verified", Toast.LENGTH_LONG).show();
                        }else if(response.equalsIgnoreCase("verified")){
                            SharedPreferences sharedPreferences = OfficerActivity.this.getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
                            //Creating editor to store values to shared preferences
                            SharedPreferences.Editor editor = sharedPreferences.edit();
                            //Adding values to editor
                            editor.putBoolean(Config.KEY_VERIFIED, true);
                            //Saving values to editor
                            editor.commit();
                        }else if(response.equalsIgnoreCase("updatefailure")){
                            forcelogout();
                            Toast.makeText(OfficerActivity.this, "Unable to Verified User\nPlease contact your administrator", Toast.LENGTH_LONG).show();
                        }else if(response.equalsIgnoreCase("multiple_access")){
                            forcelogout();
                            Toast.makeText(OfficerActivity.this, "Account already registered on different device\nNOTE: Register with multiple devices is not allowed", Toast.LENGTH_LONG).show();
                        }else{
                            forcelogout();
                            Toast.makeText(OfficerActivity.this, "There is some error from the server\nPlease report to your administrator" , Toast.LENGTH_LONG).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        //You can handle error here if you want
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                //Adding parameters to request
                params.put(Config.KEY_COMPANY, company);
                params.put(Config.KEY_NAME, name);
                params.put(Config.KEY_PASS, pass);
                params.put(Config.KEY_ANDROIDID, androidid);



                //returning parameter
                return params;
            }
        };
        //Adding the string request to the queue
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(stringRequest);
    }

    //Force Logout function
    private void forcelogout(){
        //Getting out sharedpreferences
        SharedPreferences preferences = getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
        //Getting editor
        SharedPreferences.Editor editor = preferences.edit();
        //Puting the value false for loggedin and verification
        editor.putBoolean(Config.LOGGEDIN_SHARED_PREF, false);
        editor.putBoolean(Config.KEY_VERIFIED, false);
        //Putting blank value to name & type
        editor.putString(Config.KEY_NAME, "");
        editor.putString(Config.KEY_TYPE, "");
        editor.putString(Config.LOGGEDIN_TYPE_PREF, "");
        //Saving values to editor
        editor.commit();
        //Starting login activity
        //Intent intent = new Intent(OfficerActivity.this, MainActivity.class);
        //startActivity(intent);
        finish();
    }

    //Logout function
    private void logout() {
        //Creating an alert dialog to confirm logout
        AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(this);
        alertDialogBuilder.setMessage("Are you sure you want to logout?");
        alertDialogBuilder.setPositiveButton("Yes",
                new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        //Getting out sharedpreferences
                        SharedPreferences preferences = getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
                        //Getting editor
                        SharedPreferences.Editor editor = preferences.edit();
                        //Puting the value false for loggedin and verification
                        editor.putBoolean(Config.LOGGEDIN_SHARED_PREF, false);
                        editor.putBoolean(Config.KEY_VERIFIED, false);
                        //Putting blank value to name & type
                        editor.putString(Config.KEY_NAME, "");
                        editor.putString(Config.KEY_TYPE, "");
                        editor.putString(Config.LOGGEDIN_TYPE_PREF, "");
                        //Saving values to editor
                        editor.commit();
                        //Starting login activity
                        //Intent intent = new Intent(OfficerActivity.this, MainActivity.class);
                        //startActivity(intent);
                        finish();
                    }
                });
        alertDialogBuilder.setNegativeButton("No",
                new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {

                    }
                });
        //Showing the alert dialog
        AlertDialog alertDialog = alertDialogBuilder.create();
        alertDialog.show();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        //Adding our menu to toolbar
        getMenuInflater().inflate(R.menu.menu, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        if (id == R.id.menuLogout) {
            //calling logout method when the logout button is clicked
            logout();
        }
        if(id == R.id.menuAbout){
            Intent intent = new Intent(this, About.class);
            startActivity(intent);
        }
        return super.onOptionsItemSelected(item);
    }
}
