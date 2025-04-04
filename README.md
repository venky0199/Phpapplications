**PHP deployment for linux =====================================================================**
In chrome search for xampp downloan and copy link address for linux

in linux server wget https://sourceforge.net/projects/xampp/files/XAMPP%20Linux/8.2.12/xampp-linux-x64-8.2.12-0-installer.run

 chmod +x xampp-linux-x64-8.2.12-0-installer.run (This is used to change the file to an executable file)
 
 ./xampp-linux-x64-8.2.12-0-installer.run (Execute the file) and click enter 3-4 times 
 
 There you can find the path that our xampp files installed i.e **/opt/lampp**
 
 go to the path cd **/opt/lampp/** here you can finf all the files
 
 in **htdocs** folder you can keep all the php files
 
 To access phpmyadmin in browser 
 
 go to the path /opt/lampp/etc/extra/
 
 in file vi httpd-xampp.conf in line 13 remove local (Require local) to all granted(Require all granted) you can access phpmyadmin in browser restart the lampp server by
 
 Restart : **in root /opt/lampp/lampp restart**
 
If we create image and launch and instance then again we have to start server by  **in root /opt/lampp/lampp start**

To avoid that we have to do some set up like:

in linux **crontab -e**

**@reboot /opt/lampp/lampp start**





# Phpapplications
to deploy Php website

======== PHP files ======

Launch windows Instance, connect to ROP Install google chrome, download xamppfile, In htdocs keep all the  files your want to deploy.

to check whether files are working in the localhost of RdP

to open the files In the browser, you need to some settings) windows defendar firewall =) turn windows Defender Firewall on or off

trun off windows Defender firewall 
ok.   or in windows defender firewall advanced settings go to windows defender firewall advanced properties in public profile inbound rules select allow


====== Data Base ========
For Database configaration: in RDP edge type localhost/phpmyadim

Database name = Register

Table name = Register

In table details: we have to enter below details

1. ID == A.I select checkbox
2. username, select Varchar
3. mobile , select Varchar
4. password , select Varchar

to open data your program dbname and table name should match, you can open db only on the RDp.

⇒ to open the Phpmyadmin in the browser of using link In RDp go to the xampp control Panel

>) on Apache click on config = change **local TO all granted.** Then you can See The database In the browser

in RDP WE Have to change local to all granted in **Apache(httpd Xampp.conf)** after changing we have stop apache and start again then we can access it in browser

**NOTE:**

After Creating all the setup every thing works well, but if we take the backup of the instance and again we launch the new instance from the backup taken. 

we again have to connect to RDP and again open the Xampp control panel and again we have to start Apache and SQL.

To over come this we need to follow few steps

Start menu and search for **Task Scheduler**

Select **Task Scheduler library**==> go to **Action** Tab above ==> select New folder and give new folder and create folder => ok

Go to the Folder created ==> In **Action** tab again ==> **Create task** 

In **General** => Give name => Select **Run whether user is logged on or not** => select **Run with highest priviliges**

In **Triggers** => Select **At startUP** ==> make sure Enabled check box is checked

In **Actions** => New => Browser select Xammp folder select **xampp control.exe** =>

In **condition** ==> ok

In **settions** ==>ok after that enter the password of the Ec2 instance

 You can find after restarting the RDP of that instance we can find the control panel automatically started


=========================================================


 Commands if the Apache did not start and it is occupied by PID 4(This is only if the Apache did not start and showing error)

Open CMD and run as administrator

command ==> 

net stop http

sc config http start= disabled

 

 






