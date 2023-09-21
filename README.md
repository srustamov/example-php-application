# Example PHP Application

Example PHP Application is a PHP-based project developed to showcase a sample PHP application. This project is Dockerized to ensure streamlined development, and deployment. The source code is hosted on [GitHub](https://github.com/srustamov/example-php-application).

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Getting Started](#getting-started)
   - [Clone the Repository](#clone-the-repository)
   - [Build and Run the Docker Container](#build-and-run-the-docker-container)
3. [Usage](#usage)
4. [Contributing](#contributing)

## Prerequisites
- Docker
- Docker-Compose
- Git
- PHP (if running outside Docker)
- Mysql (if running outside Docker)

## Getting Started

### Clone the Repository
To get started with the Example PHP Application, clone the repository to your local machine using the following command:
```bash
git clone https://github.com/srustamov/example-php-application.git
cd example-php-application
```

### Build and Run the Docker Container
Once you have cloned the repository, navigate to the project directory and run the following commands to build and run the Docker container:

```bash
docker-compose build
docker-compose up -d
