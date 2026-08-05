## Steps to run project

    1- open terminal and run "git clone git@github.com:SondosAbdelhameed/ElectroPiTask.git"
    2- after project downloaded open it's folder 
    3- copy .env.examble and rename it to .env then open it and update database configration
    4- open new terminal for project folder
    6- run "composer install"
    7- run "php artisan migrate:fresh --seed"
    8- run "php artisan serve"
    9- run "php artisan test" to run unit test
    # in postman
    10- import postman collection to test apis - attatched with project in "postman" folder 
    11- add new environmint to 
        base_url => "http://127.0.0.1:8000/api"
    
