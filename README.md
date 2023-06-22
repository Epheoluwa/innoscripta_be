## Innoscripta Take-Home Challenge Backend Repository

This is a Take-Home Challenge assessment, The challenge is to build a news
aggregator website that pulls articles from various sources and displays them in a clean,
easy-to-read format

•	Author: [Solomon Sunmola](https://github.com/Epheoluwa) <br>
•	Twitter: [@ifegracelife](https://twitter.com/ifegracelife) <br>
•	Portfolio: [Solomon](https://epheoluwa-portfolio.netlify.app/) <br>

### Prerequisites

•	Docker <br>
•	Docker Compose <br>

## Project Setup
```
git clone git@github.com:Epheoluwa/innoscripta_be.git
cd innoscripta_be
cd backend
```

To setup the .env file
```
cp .env.example .env 
make generate
make clear
add api key for NEWSORG_APIKEY and NYTIMES_APIKEY
setup Database Configuration
```

## Run Project
To run the project first build the docker container with the following command
```
make setup
```
After Building the project you can run the following command to start the container
```
make up
```
To stop the container run the following command
```
make stop 
```
To laravel migration you can run the following command
```
make migration 
```