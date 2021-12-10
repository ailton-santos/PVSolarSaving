# PVSolarSaving

Determine how much you can save from installing PV Solar Cells and Return on Investments.

Installation Instructions
Create a new database user and assign privileges to the user using the following commands:

CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password'; GRANT ALL PRIVILEGES ON * . * TO 'newuser'@'localhost';

Then open the php file called 'init-db.php' in your browser from the root of your web directory. This will create the database and table, and setup the required fields needed to store the calculations.
