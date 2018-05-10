package com.weebly.hackd.nfctracker;

/**
 * Created by Standard on 8/20/2016.
 */
public class Config {
    //public static final String DATA_URL = "http://localhost/appFetchAccountType.php?username=";     //Local Test
    public static final String DATA_URL = "http://<your domain name>/appFetchAccountType.php?username=";   //Server
    //public static final String UPDATE_RECORD = "http://localhost/appTagStoring.php";    //Local Test
    public static final String UPDATE_RECORD = "http://<your domain name>/appTagStoring.php"; //Server
    //public static final String USER_VERIFY = "http://localhost/userVerifier.php";    //Local Test
    public static final String USER_VERIFY = "http://<your domain name>/userVerifier.php";    //Server
    //public static final String WEB_LOGIN = "http://localhost/";     //Local Test
    public static final String WEB_LOGIN = "http://<your domain name>/";     //Server

    //$_GET REQUEST
    public static final String KEY_NAME = "name";
    public static final String KEY_TYPE = "type";
    public static final String KEY_COMPANY = "company";
    //$_POST REQUEST
    public static final String KEY_PASS = "pass";
    public static final String KEY_TAG = "tag";

    //Sharedpreference
    public static final String SHARED_PREF_NAME = "myloginapp";
    public static final String LOGGEDIN_SHARED_PREF = "loggedin";
    public static final String LOGGEDIN_TYPE_PREF = "null";

    public static final String KEY_ANDROIDID = "androidid";
    public static final String KEY_VERIFIED = "isverified";

    public static final String JSON_ARRAY = "result";
}
