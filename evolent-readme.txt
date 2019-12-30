This file is created to demonstrate "Evolent Contact Details Demo Project"

 Installion
------------------------------------------------------------------------------------------------------------------------
Step1: Download zip folder for website source code and database file.
Step2: Unzip downloaded zip file.
Step3: Open drupal 8 suppoted enviorment like XAMMP, MAMP or WAMP.
Step4: Copy source code in require folder (FOr eg: XAMMP put it onto XAMPP⁩ ▸ ⁨xamppfiles⁩ ▸ ⁨htdocs⁩ folder ▸ ⁨evolent)
Step5: Opne PHPMyAdmin console of XAMMP and create new databse with below details.

$databases['default']['default'] = array (
  'database' => 'evolent_db',
  'username' => 'root',
  'password' => '',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

Step6: import evolent_db.sql file that you already downloaded.
Step7: Ensure that there will be total 74 tables created.

Step8: Open any Drupal 8 suppoted web browser to above directory path (like http://localhost/evolent)
Step9: You will see the Evolent Demo Drupal 8 website.

Step10: Use below login credential to login as admin user.
username: evolent
password: Evolent@123!

Contact Demo details
------------------------------------------------------------------------------------------------------------------------
Step1: Please click on "Add Contact" main menu link. 
You will get contant deatail form, please fill require value and submit.

Step2: Once you push submit button it will add a contact record into databse after proper data validation.
Step3: After this you will redireted to "Contcat List Section", 
Step4: Here you will find all conatct details and you can edit, delete, sort and move to next and preview page.

Drupal Module details
------------------------------------------------------------------------------------------------------------------------
Here I creted one custom module using drupal fprm API to add, edit and delete Contcat
Please have a look evolent_contact.info.yml file for more details.
custom module name: evolent_contact
path: XAMPP⁩ ▸ ⁨xamppfiles⁩ ▸ ⁨htdocs⁩ ▸ ⁨evolent⁩ ▸ ⁨modules⁩ ▸ ⁨custom⁩ ▸ evolent_contact

Also for contact list I creaded a drupal view table.
admin path: http://localhost/evolent/admin/structure/views/view/evolent_contact_demo

Also here I used two druapl constributed moduel
1. view_custom_table module
2. view_conditional module

