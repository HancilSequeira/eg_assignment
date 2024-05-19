# Calendar Assignment


## pre-requisite 
* Install docker

## Project setup
* After cloning the project run the following for 1st time
  * `docker-compose up -d --build` this will take time for the first time and later on run the only this `docker-compose up -d`
  * Now docker container using this `docker exec -it eg-assignment-php-fpm bash`
  * Once inside the docker run `composer install`
* Now run the following URL in browser `http://localhost:8000/api/doc` click on the API route click on `Try it out` and give necessary param and click on execute
* This can be executed through postman also
