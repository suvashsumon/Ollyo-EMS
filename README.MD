## Live Link
Link : https://ollyo-ems.onrender.com

****N:B:*** Due to ***Render***'s policy, this free instance will spin down with inactivity, which can delay requests by 50 seconds or more.*

```
# Admin
- Email : admin@admin.com
- pass : 123456789

# User
- Email : user@user.com
- pass : 123456789
```
API endpoint : `http://localhost/Ollyo-EMS/api/event_details.php?event_id=eventid`

## Database Diagram
![database](database_er.png)

## Setup Guide
This applicaton already hosted on ***Render***. But in case of local setup, follow the follwing guidelines:
1. To develop this project following technologies are used:
    ```
    php-8.3.11
    mysql-8.0.30
    apache-2.0
    ```
    You can install those as a bundle with ***Laragon*** or ***XAMPP***.

2. Place the project on root folder of your server.

3. Create a databse on mysql and export the provided `db.sql` file which will create nessesery tables.

4. Create a `.env` file on the project root. Add the following credential with your own:
    ```
    DB_HOST=
    DB_PORT=
    DB_NAME=
    DB_USER=
    DB_PASS=
    ```

5. Ok, you are good to go. Nevigate to `http://localhost/Ollyo-EMS`. You should see the home page.