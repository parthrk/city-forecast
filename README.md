Please follow below steps to set up project in your local

Prerequisites: Docker has to be installed

1. git clone https://github.com/parthrk/city-forecast.git
2. cd city-forecast
3. docker run --rm --interactive --tty -v $(pwd):/app composer install
4. cp .env.example .env
5. ./vendor/bin/sail up
6. ./vendor/bin/sail artisan key:generate
7. ./vendor/bin/sail artisan migrate
8. ./vendor/bin/sail npm install
9. ./vendor/bin/sail npm run dev
