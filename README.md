[Project address](https://mshaian.com)

[Project Documentation](https://mshaian.com/doc/soft_monitor/)

"Soft Monitor" web-system documentation.
=========================================

## 1. The purpose of this application.

"Soft Monitor" web-application was made to monitor status and failures of different equipment that use SNMP, such as Power Systems and telecommunication devices, using Apache/Nginx + MySQL + PHP server as a basis. It was designed for dispatchers who can detect a malfunction quickly using the application's interface and functions.

This system adjusts to any types of equipment by configuring equipment and connection templates. It already contains templates for Power Systems Eaton APS and PW9130, Elteco BZX and Socomec PS. However, it is possible to configure any type of assessed information from devices.

The application can gather malfunction history information and saves this data. Its performance allows the addition of thousands of devices, and RAM usage can also be configured.

At first, you need a Windows web server Apache/Nginx + MySQL + PHP.
You can find an instruction here: [Apache MySQL PHP Installation](https://mshaian.com/doc/soft_monitor/amp_en.php)

Requirements:
Apache 2.4+
PHP 7.2+ (with snmp package)
MySQL 5.7+

## 2. Soft Monitor User's Guide.

### 2.1 Installation and Run.
Copy all the application's to the web-server docs directory. Open your browser and input the web-server address, or http://localhost if it is the same computer. You will see the installation dialogue:

Input your database parameters and change other server variables. You can leave default values. Then check them by pressing "Check" button.


If everything is correct, you can press "Install" button. After the end of the installation process, you will see the main page.


By this moment, the system has only one user with admin rights. To login input Login: admin, Password: admin. You will see the next main page.


Here you can Run/Stop monitoring server. This page is available to users with administrator rights only.

If you want to install the server from scratch and erase all existing data, you can input the address:
**USE VERY CAREFULLY AS YOU CAN LOSE ALL YOUR DATA.**
http://localhost/admin/reinstall
Only an administrator can run this option.

### 2.2 Admin menu.
In the right-upper corner, admins can see the admin menu.

#### 2.2.1 Devices management.

On this page, you can add, modify and delete devices. Each device has its name, IP-address (you can use a domain name as well), a template that determines the type of data it sends, connection template is responsible for SNMP-connection parameters. Ping attempts and timeout are used not only for ICMP-requests but for SNMP too. Group is used for users without administrator rights. Each device is shown only for users of their group.

You can use "Search" to find a certain device and sort any column by clicking on their header.

#### 2.2.2 User management.

On this page, administrators can modify, delete and add user groups and users and modify users passwords. Be careful with administrator groups and users. There should be at least one user group with Admin rights and one user in this group.

#### 2.2.3 Default settings.

This section contains the most valuable settings and options for the server.

"Apply new licence file". A default licence allows adding five devices. However, you can acquire a new licence file on the project's main page. Just upload a new licence file.
"Clear log data" button erases all log data. You can save it in advance on "Log" page.
"Number of running processes" determines how many php.exe processes are used simultaneously. You can increase the server's performance, but do not overload RAM and processor.
"Default recovery time" is a period in seconds in which an alarm is counted as completely cleared after recovery. You can change this parameter for each particular device.
"Default ping attempts" and "Default ping timeout" are used when no specific value was set during a device adding.

#### 2.2.4 Connection templates.

Each connection template contains specific data for SNMP-connection. If you use SNMPv1, setting a password is not necessary.

#### 2.2.5 Device templates.

By clicking on a template name, you'll see it's settings:


Each template contains custom OIDs with low and high thresholds, an alarm message and a severity level. You can add, modify and delete them. Learn SNMP-documentation of each type of equipment you want to use. This server does not receive SNMP-traps; so, there is no need to configure each device for working with this system.

You can Import and Export templates as file and share them. The basic version already contains templates for Power System Equipment, such as Eaton, Elteco and Socomec.

### 2.3 User's menu.

#### 2.3.1 Alarms page.

This page shows all the alarms. New alarms blink, but a user can press "Acknowledge" to make sure that the alarm was registered.

#### 2.3.2 Device list page.

Here you can see all devices and their alarms severity levels.

#### 2.3.3 Log page.

All log data is saved in a database. You can download it as a table or show a specific period data.
