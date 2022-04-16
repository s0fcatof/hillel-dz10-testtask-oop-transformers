# Transformers

## Install dependencies
```
docker run --rm --interactive --tty --volume $(pwd):/app composer install
```

## Configure container
###### (Do only if you are going to use web)
1) Copy `.env.example` to `.env` and set the preferred Apache port.
2) Run command ```docker-compose up -d```

## Run unit tests

### All tests
```
docker run -it --rm -v $(pwd):/app -w /app php:8.1.4-cli ./vendor/bin/phpunit --do-not-cache-result
```

### Specific test
E.g. to run `testCreate` from `./test/TransformersTest.php` use command:
```
docker run -it --rm -v $(pwd):/app -w /app php:8.1.4-cli ./vendor/bin/phpunit --do-not-cache-result --filter testCreate$
```

## Run code sniffer
E.g. to check `src` folder against the `PSR-12` coding standard 
```
docker run --rm -v $(pwd):/data cytopia/phpcs --standard=PSR12 src
```