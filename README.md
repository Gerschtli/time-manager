# Time Manager [![Build Status](https://travis-ci.org/Gerschtli/time-manager.svg?branch=master)](https://travis-ci.org/Gerschtli/time-manager) [![Test Coverage](https://codeclimate.com/github/Gerschtli/time-manager/badges/coverage.svg)](https://codeclimate.com/github/Gerschtli/time-manager/coverage)

## API Documentation

### Endpoints

| URL           | Method | Input         | Description    |
|---------------|--------|---------------|----------------|
| /api/task     | GET    | -             | Get all tasks  |
| /api/task     | POST   | Task Object   | Add a new task |
| /api/task/:id | GET    | -             | Get a task     |
| /api/task/:id | PUT    | Task Object   | Update task    |
| /api/task/:id | DELETE | -             | Delete task    |

### Objects

#### Task

```json
{
    "taskId": 4,
    "description": "Description",
    "times": [
        {
            "timeId": 5,
            "start": "YYYY-MM-DD HH:MM:SS",
            "end": "YYYY-MM-DD HH:MM:SS"
        }
    ]
}
```

## Setup Application

### Configuration

Copy templates and modify if needed (default Settings match up with Vagrant Database)

```bash
$ cp app/parameter.ini.dist app/parameter.ini
$ cp phinx.yml.dist phinx.yml
```

### Download dependencies

```bash
$ composer install
$ npm install
$ npm install -g gulp
```

### Vagrant

```bash
$ vagrant up
```

### Migrate Database

SSH into Vagrant machine

```bash
$ vagrant ssh
$ cd /var/www/time-manager
```

#### Migrate Database scheme

```bash
$ php bin/phinx migrate
```

##### Rollback

```bash
$ php bin/phinx rollback
```

#### Insert Test Data

```bash
$ php bin/phinx seed:run
```

### Start Gulp

#### Build

```bash
$ gulp build
```

#### Watch

```bash
$ gulp watch
```

#### Build + Watch

```bash
$ gulp
```

##### Load offline versions of cdn libraries

```bash
$ gulp [build|watch] --offline
```

##### Production (minified CSS/JS/HTML)

```bash
$ gulp [build|watch] --production
```
