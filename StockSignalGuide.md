SINI LIAT  TUTORIAL JALANIN STOCK SIGNAALNYA BOOOS


stock signal(dummy) is checked every 5 minutes by the scheduled job set on console.php

to run, you run these at 3 separate consoles.

    php artisan queue:work

    php artisan serve
    php artisan schedule:work