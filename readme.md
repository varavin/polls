### Why's that for?

This is a sample polling website/webapp implemented as a test task. 

One of the main requirements was to avoid using any PHP or JS frameworks. Everything is made from scratch on plain PHP and vanilla JS. The only exception is the Ratchet websockets library.    

### What does it do? 
  
* On the main page user can create poll with arbitrary number of options.

* After creating the poll user is redirected to the poll page, which has a "secret" URL (like "/poll/c4b3090495e11d64feface1a790089d6/") which is available for everyone and can be shared.

* Every visitor can vote for every poll, but only once per browser. There's no serious protection, though - cleaning the cookies will allow to vote again.

* On every poll page there is a list of results which is updated in real time when someone votes for that poll. The update is implemented via websockets. 

### How to run?

* Clone the repository and run `composer install`.

* Create the database from the dump file: `database/database.sql`.

* Start the web server using `public` folder as server root folder.

* Specify the database and websocket parameters in `config/config.production.php`. You must also specity the `siteRootURL` param there - fully qualified URL website home page.  

* Start the websocket server `console/ws.php`. The app will work without it, but it's required to update the polling results in real time (you'll have to reload the page manually without it).

 



