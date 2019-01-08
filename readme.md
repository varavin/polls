### Why's that for?

This is a sample polling website/webapp implemented as a test task. 

One of the main requirements was to avoid using any PHP or JS frameworks. Everything is made from scratch on plain PHP and vanilla JS. The only exceptions are Ratchet (for websockets) and PHPUnit (for autotests).    

### What does it do? 
  
* On the main page user can create poll with arbitrary number of options.

* After creating the poll user is redirected to the poll page, which has a "secret" URL (like `/poll/c4b3090495e11d64feface1a790089d6/`) which is available for everyone and can be shared.

* Every visitor can vote for every poll, but only once per browser. There's no serious protection, though - cleaning the cookies will allow to vote again.

* On every poll page there is a list of results which is updated in real time when someone votes for that poll. The update is implemented via websockets. 

### How to run?

* Clone the repository and run `composer install`.

* Create the database from the dump file: `./database/database.sql`.

* Create the test database (to be used for autotests) from the same dump file: `./database/database.sql`.

* Start the web server using `./public` folder as server root folder.

* Fill the database and websocket parameters in `./config/config.production.php`. You must also fill the `siteRootURL` param there - fully qualified URL of the website home page (no trailing slash). Note the `db_tests` group of parameters - they are specifying the database for autotests.   

* Start the websocket server: `php ./console/ws.php`. The app will work without it, but websocket server is required to update the polling results in real time.

* Open the home page in browser, create the poll and try to vote. Run other browsers or "incognito mode" browser instances to imitate multiple visitors.

### Tests

* To run autotests, run `./vendor/bin/phpunit --bootstrap vendor/autoload.php tests`. 

* To be honest, the test coverage is far from full, since it was a test task and I had limited time. But there are some tests for models and services, including a few that use test database. 

 



