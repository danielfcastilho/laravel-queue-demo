## Requirements
- Github CLI installed
- Docker desktop installed

## Running the app
- Clone the project `git@github.com:danielfcastilho/laravel-queue-demo.git`
- Go inside the project root folder `cd laravel-queue-demo/`
- Checkout to the demo branch `git checkout task`
- Make sure docker desktop is running
- run `docker compose up -d`
- Make sure the containers are fully booted
- run `docker exec -it queue-demo-server php artisan migrate`
- run `docker exec -it queue-demo-server php artisan queue:work`
- Hit the API using http://localhost:8000

## Testing
- run `docker exec -it queue-demo-server php artisan test`