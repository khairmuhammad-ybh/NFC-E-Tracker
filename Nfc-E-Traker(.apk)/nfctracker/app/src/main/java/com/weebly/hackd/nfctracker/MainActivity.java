package com.weebly.hackd.nfctracker;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class MainActivity extends AppCompatActivity implements View.OnClickListener {

    private String name ="";
    private EditText username,password;
    private Button buttonGet;
    private boolean loggedIn = false;
    private String sharedType = "null";

    //Auto Update (Auto-Update-Apk)
    private AutoUpdateApk aua;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        aua = new AutoUpdateApk(getApplicationContext());

        username = (EditText)findViewById(R.id.etUser);
        password = (EditText)findViewById(R.id.etpassword);
        buttonGet = (Button)findViewById(R.id.btnSend);

        buttonGet.setOnClickListener(this);


    }

    @Override
    protected void onResume(){
        super.onResume();
        //In onresume fetching value from sharedpreference
        SharedPreferences sharedPreferences = getSharedPreferences(Config.SHARED_PREF_NAME,Context.MODE_PRIVATE);

        //Fetching the boolean value form sharedpreferences
        loggedIn = sharedPreferences.getBoolean(Config.LOGGEDIN_SHARED_PREF, false);
        sharedType =sharedPreferences.getString(Config.LOGGEDIN_TYPE_PREF,Config.KEY_TYPE);

        //If we will get true
        if(loggedIn && sharedType.equalsIgnoreCase("1")){
            //Start the Profile Activity
            Intent intent = new Intent(this, AdminActivity.class);
            startActivity(intent);
        }else if(loggedIn && sharedType.equalsIgnoreCase("2")){
            //Start the Profile Activity
            Intent intent = new Intent(this, OfficerActivity.class);
            startActivity(intent);
        }else if(loggedIn && sharedType.equalsIgnoreCase("3")){
            //Start the Profile Activity
            Intent intent = new Intent(this, WorkerActivity.class);
            startActivity(intent);
        }
    }
    
    public void getData(){
        String user = username.getText().toString().trim();
        String pass = password.getText().toString().trim();
        if(user.equals("") && pass.equals("")){
            Toast.makeText(this,"Please enter a username & password",Toast.LENGTH_LONG).show();
            return;
        }else if(user.equals("")){
            Toast.makeText(this,"Please enter a username",Toast.LENGTH_LONG).show();
            return;
        }else if(pass.equals("")){
            Toast.makeText(this,"Please enter a password",Toast.LENGTH_LONG).show();
            return;
        }
        final ProgressDialog loading = ProgressDialog.show(this, "Please wait...", "Fetching...", false, false);

        String url = Config.DATA_URL+username.getText().toString().trim()+"&password="+password.getText().toString().trim();

        StringRequest stringRequest = new StringRequest(url, new Response.Listener<String>() {

            @Override
            public void onResponse(String response) {
                loading.dismiss();
                showJSON(response);
            }
        },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(MainActivity.this,error.getMessage().toString(),Toast.LENGTH_LONG).show();
                    }
                });
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(stringRequest);
    }

    private void showJSON(String response) {
        String name="";
        String type="";
        String company="";
        try{
            JSONObject jsonObject = new JSONObject(response);
            JSONArray result = jsonObject.getJSONArray(Config.JSON_ARRAY);
            JSONObject collectData = result.getJSONObject(0);
            name = collectData.getString(Config.KEY_NAME);
            type = collectData.getString(Config.KEY_TYPE);
            company = collectData.getString(Config.KEY_COMPANY);
        }catch(JSONException e){
            e.printStackTrace();
        }
        if(type.equals("1")){
            //String user = username.getText().toString();
            String pass = password.getText().toString();
            //Creating shared preference
            SharedPreferences sharedPreferences = MainActivity.this.getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
            //Creating editor to store values to shared preferences
            SharedPreferences.Editor editor = sharedPreferences.edit();
            //Adding values to editor
            editor.putBoolean(Config.LOGGEDIN_SHARED_PREF, true);
            editor.putString(Config.KEY_NAME, name);
            editor.putString(Config.KEY_PASS, pass);
            editor.putString(Config.LOGGEDIN_TYPE_PREF, type);
            editor.putString(Config.KEY_COMPANY, company);
            //Saving values to editor
            editor.commit();
            //Create and start Intent
            Intent intent = new Intent(this,AdminActivity.class);
            startActivity(intent);
            username.setText("");
            password.setText("");
        }else if(type.equals("2")){
            //String user = username.getText().toString();
            String pass = password.getText().toString();
            //Creating shared preference
            SharedPreferences sharedPreferences = MainActivity.this.getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
            //Creating editor to store values to shared preferences
            SharedPreferences.Editor editor = sharedPreferences.edit();
            //Adding values to editor
            editor.putBoolean(Config.LOGGEDIN_SHARED_PREF, true);
            editor.putString(Config.KEY_NAME, name);
            editor.putString(Config.KEY_PASS, pass);
            editor.putString(Config.LOGGEDIN_TYPE_PREF, type);
            editor.putString(Config.KEY_COMPANY, company);
            //Saving values to editor
            editor.commit();
            //Create and start Intent
            Intent intent = new Intent(this,OfficerActivity.class);
            startActivity(intent);
            username.setText("");
            password.setText("");
        }else if(type.equals("3")){
            String user = username.getText().toString();
            String pass = password.getText().toString();
            //Creating shared preference
            SharedPreferences sharedPreferences = MainActivity.this.getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
            //Creating editor to store values to shared preferences
            SharedPreferences.Editor editor = sharedPreferences.edit();
            //Adding values to editor
            editor.putBoolean(Config.LOGGEDIN_SHARED_PREF, true);
            editor.putString(Config.KEY_NAME, name);
            editor.putString(Config.KEY_PASS, pass);
            editor.putString(Config.LOGGEDIN_TYPE_PREF, type);
            editor.putString(Config.KEY_COMPANY, company);
            //Saving values to editor
            editor.commit();
            //Create and start Intent
            Intent intent = new Intent(this,WorkerActivity.class);
            startActivity(intent);
            username.setText("");
            password.setText("");
        }
        else
            Toast.makeText(this,"Username/Password is invalid",Toast.LENGTH_LONG).show();

    }

    @Override
    public void onClick(View v) {
        getData();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        //Adding our menu to toolbar
        getMenuInflater().inflate(R.menu.menu, menu);
        menu.findItem(R.id.menuLogout).setVisible(false);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        if(id == R.id.menuAbout){
            Intent intent = new Intent(this, About.class);
            startActivity(intent);
        }
        return super.onOptionsItemSelected(item);
    }

}
