package com.weebly.hackd.nfctracker;

import android.content.Context;
import android.content.DialogInterface;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.nfc.NfcAdapter;
import android.provider.Settings;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.RelativeLayout;
import android.widget.TextView;


import java.io.UnsupportedEncodingException;
import java.util.Arrays;
import java.util.HashMap;
import java.util.Map;

import android.app.Activity;
import android.app.PendingIntent;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.IntentFilter.MalformedMimeTypeException;
import android.nfc.NdefMessage;
import android.nfc.NdefRecord;
import android.nfc.Tag;
import android.nfc.tech.Ndef;
import android.os.AsyncTask;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;


public class WorkerActivity extends AppCompatActivity {

    public static final String MIME_TEXT_PLAIN = "text/plain";
    public static final String TAG = "NfcDemo";


    private TextView mTextView;
    private NfcAdapter mNfcAdapter;
    private RelativeLayout mRelativeLayout;

    private TextView currentUser;

    //Auto Update (Auto-Update-Apk)
    private AutoUpdateApk aua;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_worker);

        aua = new AutoUpdateApk(getApplicationContext());

        mapUserToPhone();
        currentUser = (TextView)findViewById(R.id.etView);
        SharedPreferences sharedPreferences = getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
        String user = sharedPreferences.getString(Config.KEY_NAME,"Not Available");
        currentUser.setText("Welcome "+ user);

        mTextView = (TextView) findViewById(R.id.textView_explanation);
        mRelativeLayout = (RelativeLayout) findViewById(R.id.mainbackground);

        mNfcAdapter = NfcAdapter.getDefaultAdapter(this);

        if (mNfcAdapter == null) {
            // Stop here, we definitely need NFC
            Toast.makeText(this, "This device doesn't support NFC.", Toast.LENGTH_LONG).show();
            finish();
            return;

        }

        if (!mNfcAdapter.isEnabled()) {
            mTextView.setText("NFC is disabled.");
        } else {
            mTextView.setText("NFC is enable.\nScan a tag");
        }

        handleIntent(getIntent());
    }


    //map user account with android id
    private void mapUserToPhone() {

        String aID = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);

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
                            SharedPreferences sharedPreferences = WorkerActivity.this.getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
                            //Creating editor to store values to shared preferences
                            SharedPreferences.Editor editor = sharedPreferences.edit();
                            //Adding values to editor
                            editor.putBoolean(Config.KEY_VERIFIED, true);
                            //Saving values to editor
                            editor.commit();
                            //Alert
                            Toast.makeText(WorkerActivity.this, "User account successfully verified", Toast.LENGTH_LONG).show();
                        }else if(response.equalsIgnoreCase("verified")){
                            SharedPreferences sharedPreferences = WorkerActivity.this.getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
                            //Creating editor to store values to shared preferences
                            SharedPreferences.Editor editor = sharedPreferences.edit();
                            //Adding values to editor
                            editor.putBoolean(Config.KEY_VERIFIED, true);
                            //Saving values to editor
                            editor.commit();
                        }else if(response.equalsIgnoreCase("updatefailure")){
                            forcelogout();
                            Toast.makeText(WorkerActivity.this, "Unable to Verified User\nPlease contact your administrator", Toast.LENGTH_LONG).show();
                        }else if(response.equalsIgnoreCase("multiple_access")){
                            forcelogout();
                            Toast.makeText(WorkerActivity.this, "Account already registered on different device\nNOTE: Register with multiple devices is not allowed", Toast.LENGTH_LONG).show();
                        }else{
                            forcelogout();
                            Toast.makeText(WorkerActivity.this, "There is some error from the server\nPlease report to your administrator" , Toast.LENGTH_LONG).show();
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


    @Override
    protected void onResume() {
        super.onResume();

        /**
         * It's important, that the activity is in the foreground (resumed). Otherwise
         * an IllegalStateException is thrown.
         */
        setupForegroundDispatch(this, mNfcAdapter);
    }

    @Override
    protected void onPause() {
        /**
         * Call this before onPause, otherwise an IllegalArgumentException is thrown as well.
         */
        stopForegroundDispatch(this, mNfcAdapter);

        super.onPause();
    }

    @Override
    protected void onNewIntent(Intent intent) {
        /**
         * This method gets called, when a new Intent gets associated with the current activity instance.
         * Instead of creating a new activity, onNewIntent will be called. For more information have a look
         * at the documentation.
         *
         * In our case this method gets called, when the user attaches a Tag to the device.
         */
        handleIntent(intent);
    }

    private void handleIntent(Intent intent) {
        // TODO: handle Intent
        String action = intent.getAction();
        if (NfcAdapter.ACTION_NDEF_DISCOVERED.equals(action)) {

            String type = intent.getType();
            if (MIME_TEXT_PLAIN.equals(type)) {

                Tag tag = intent.getParcelableExtra(NfcAdapter.EXTRA_TAG);
                new NdefReaderTask().execute(tag);

            } else {
                Log.d(TAG, "Wrong mime type: " + type);
            }
        } else if (NfcAdapter.ACTION_TECH_DISCOVERED.equals(action)) {

            // In case we would still use the Tech Discovered Intent
            Tag tag = intent.getParcelableExtra(NfcAdapter.EXTRA_TAG);
            String[] techList = tag.getTechList();
            String searchedTech = Ndef.class.getName();

            for (String tech : techList) {
                if (searchedTech.equals(tech)) {
                    new NdefReaderTask().execute(tag);
                    break;
                }
            }
        }
    }

    /**
     * @param activity The corresponding {@link Activity} requesting the foreground dispatch.
     * @param adapter The {@link NfcAdapter} used for the foreground dispatch.
     */
    public static void setupForegroundDispatch(final Activity activity, NfcAdapter adapter) {
        final Intent intent = new Intent(activity.getApplicationContext(), activity.getClass());
        intent.setFlags(Intent.FLAG_ACTIVITY_SINGLE_TOP);

        final PendingIntent pendingIntent = PendingIntent.getActivity(activity.getApplicationContext(), 0, intent, 0);

        IntentFilter[] filters = new IntentFilter[1];
        String[][] techList = new String[][]{};

        // Notice that this is the same filter as in our manifest.
        filters[0] = new IntentFilter();
        filters[0].addAction(NfcAdapter.ACTION_NDEF_DISCOVERED);
        filters[0].addCategory(Intent.CATEGORY_DEFAULT);
        try {
            filters[0].addDataType(MIME_TEXT_PLAIN);
        } catch (MalformedMimeTypeException e) {
            throw new RuntimeException("Check your mime type.");
        }

        adapter.enableForegroundDispatch(activity, pendingIntent, filters, techList);
    }

    /**
     * @param activity The corresponding {@link BaseActivity} requesting to stop the foreground dispatch.
     * @param adapter The {@link NfcAdapter} used for the foreground dispatch.
     */
    public static void stopForegroundDispatch(final Activity activity, NfcAdapter adapter) {
        adapter.disableForegroundDispatch(activity);
    }


    //Read TAG
    /**
     * Background task for reading the data. Do not block the UI thread while reading.
     *
     * @author Ralf Wondratschek
     *
     */
    private class NdefReaderTask extends AsyncTask<Tag, Void, String> {

        @Override
        protected String doInBackground(Tag... params) {
            Tag tag = params[0];

            Ndef ndef = Ndef.get(tag);
            if (ndef == null) {
                // NDEF is not supported by this Tag.
                return null;
            }

            NdefMessage ndefMessage = ndef.getCachedNdefMessage();

            NdefRecord[] records = ndefMessage.getRecords();
            for (NdefRecord ndefRecord : records) {
                if (ndefRecord.getTnf() == NdefRecord.TNF_WELL_KNOWN && Arrays.equals(ndefRecord.getType(), NdefRecord.RTD_TEXT)) {
                    try {
                        return readText(ndefRecord);
                    } catch (UnsupportedEncodingException e) {
                        Log.e(TAG, "Unsupported Encoding", e);
                    }
                }
            }

            return null;
        }

        private String readText(NdefRecord record) throws UnsupportedEncodingException {
        /*
         * See NFC forum specification for "Text Record Type Definition" at 3.2.1
         *
         * http://www.nfc-forum.org/specs/
         *
         * bit_7 defines encoding
         * bit_6 reserved for future use, must be 0
         * bit_5..0 length of IANA language code
         */

            byte[] payload = record.getPayload();

            // Get the Text Encoding
            String textEncoding = ((payload[0] & 128) == 0) ? "UTF-8" : "UTF-16";

            // Get the Language Code
            int languageCodeLength = payload[0] & 0063;

            // String languageCode = new String(payload, 1, languageCodeLength, "US-ASCII");
            // e.g. "en"

            // Get the Text
            return new String(payload, languageCodeLength + 1, payload.length - languageCodeLength - 1, textEncoding);
        }

        @Override
        protected void onPostExecute(String result) {
            if (result != null) {
                //Getting out sharedpreferences
                SharedPreferences preferences = getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
                //Getting editor
                SharedPreferences.Editor editor = preferences.edit();
                //Adding values to editor
                editor.putString(Config.KEY_TAG, result);
                //Saving values to editor
                editor.commit();

                //mTextView.setText(result);
                updateRecord();
            }
        }
    }

    private void updateRecord() {

        SharedPreferences preferences = getSharedPreferences(Config.SHARED_PREF_NAME, Context.MODE_PRIVATE);
        //Fetching values form sharedpreferences
        final String company = preferences.getString(Config.KEY_COMPANY,"null");
        final String name = preferences.getString(Config.KEY_NAME, "null");
        final String type = preferences.getString(Config.LOGGEDIN_TYPE_PREF, "null");
        final String tag = preferences.getString(Config.KEY_TAG, "null");

        //Creating a string request
        StringRequest stringRequest = new StringRequest(Request.Method.POST, Config.UPDATE_RECORD,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        if (response.equalsIgnoreCase("success")) {
                            mRelativeLayout.setBackgroundColor(Color.GREEN);
                            mTextView.setText("Tag Recored.\nScan a new Tag");
                            Toast.makeText(WorkerActivity.this, "Record added", Toast.LENGTH_LONG).show();
                        }else if(response.equalsIgnoreCase("duplicate")){
                            mRelativeLayout.setBackgroundColor(Color.RED);
                            mTextView.setText("Record not added due to\n'Multiple Entry'");
                            Toast.makeText(WorkerActivity.this, "Not allowed for multiple entry", Toast.LENGTH_LONG).show();
                        }else if(response.equalsIgnoreCase("failure")){
                            Toast.makeText(WorkerActivity.this, "Unable to add records to database\nPlease contact your administrator", Toast.LENGTH_LONG).show();
                        }else{
                            Toast.makeText(WorkerActivity.this, "There is some error from the server\nPlease report to your administrator" , Toast.LENGTH_LONG).show();
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
                params.put(Config.KEY_TYPE, type);
                params.put(Config.KEY_TAG, tag);


                //returning parameter
                return params;
            }
        };
        //Adding the string request to the queue
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(stringRequest);
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
                        //Puting the value false for loggedin
                        editor.putBoolean(Config.LOGGEDIN_SHARED_PREF, false);
                        //Putting blank value to name & type
                        editor.putString(Config.KEY_NAME, "");
                        editor.putString(Config.KEY_TYPE, "");
                        editor.putString(Config.LOGGEDIN_TYPE_PREF, "");
                        //Saving values to editor
                        editor.commit();
                        //Starting login activity
                        //Intent intent = new Intent(WorkerActivity.this, MainActivity.class);
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
