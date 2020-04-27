# Coffee Feed

## About
Symfony console program that parses a local or remote XML file and inserts the data into a Google Spreadheet using Google Sheets API

## Setup
- make sure you have docker installed
- from the project folder build the docker container (*this already installs all composer dependencies*)
    1. `docker-compose build`
    2. `docker-compose up -d` 
- connect to the docker container
    - `docker exec -it coffee_feed_cli_1 bash`

## How to use
*Commands 2-4 should be run inside the docker container*
1. Enable the Google Sheets API from *https://developers.google.com/sheets/api/quickstart/php?authuser=2#step_1_turn_on_the* 
2. Configure the local API client running `php application.php app:configure-json`
3. Delete local API credentials running `php application.php app:delete-credentials`
4. Create Spreadsheet from XML running `php application.php app:process-file`
    1. Input the XML file path when prompted (default is the local xml example)
    2. Input the Spreadsheet name
    3. On first run, or when token expires, you will be prompted for verification code using the given link
    
## Components
- Docker image built from php-fpm 7.4.1
- Minimal components installed:
    - symfony console
    - google sheets API

