# NFC-E-Tracker
A mobile tracker app to track onsite workers with their mobile phone. Make an attendance sign-in/sign-out system with mobile devices. Can be used for construction or maintenance companies, where they require a system to track their onsite workers on duty.

## DISCLAIMER
This is my few android projects i did on my own where i explore almost all of android features to expands my knowledge into android programming and improve my learning curves.

## SPECIFICATION

###### REQUIREMENTS
1. Only can be use with NFC-capable Phone
2. NFC chip is required (Depends on how many you need)
3. Mobile data is a must for the app to properly function

###### HOW TO USE
1. Admin create an account for intended workers
2. Worker login from their phone
3. Turn on NFC features
4. Start scanning

###### SECURITY
Sufficent enough to protect from SQL-injection and basic instrusions, every mobile users have a binding on their first login (MAC-Address binding) to prevent from users who tried to cheat the system.

## SETUP
###### DATABASE
1. Import the SQL file in your phpmyadmin or Mysql Workbench

   > Filename: _nfctracker.sql_

2. Change to your database information in _db.php_

   > Path: _Nfc-E-Tracker(Server)\db.php_
```
<?php
$connection = mysql_connect('localhost', 'root', 'pass');
if (!$connection){
 die("Database Connection Failed" . mysql_error());
}
$select_db = mysql_select_db('db_name');
if (!$select_db){
 die("Database Selection Failed" . mysql_error());
}
?>
```

###### ANDROID APP
1. Open the project in 'Android Studio'

   > Path: _Nfc-E-Traker(.apk)\nfctracker_

2. Build project (Fix gradle issue first before build)
3. Go to Build -> Build APK(s)
4. Install to mobile phone and start using it


###### CREDENTIAL
> Username: admin

> Password: adminpass

###### NOTES
- Password hashing using **md5**
- One time binding user account with phone's MAC-Address (Excluding Admin and Officers user)
- **_Web Browser_** (for Admin & Officer)
- **_Android App_** (for Onsite users)
- There is a reset function for admin to reset the MAC-Address if user were to change/lost phone




