# Time Manager [![Build Status](https://travis-ci.org/Gerschtli/time-manager.svg?branch=master)](https://travis-ci.org/Gerschtli/time-manager) [![Test Coverage](https://codeclimate.com/github/Gerschtli/time-manager/badges/coverage.svg)](https://codeclimate.com/github/Gerschtli/time-manager/coverage)

## API Endpoints

| URL           | Method | Input         | Description    |
|---------------|--------|---------------|----------------|
| /api/mail     | POST   | -             | Send mail      |
| /api/mail     | GET    | -             | Get mail text  |
| /api/task     | POST   | Task Object   | Add a new task |
| /api/task/:id | GET    | -             | Get a task     |
| /api/task/:id | PUT    | Task Object   | Update task    |
| /api/task/:id | DELETE | -             | Delete task    |

## API Objects

#### Task

```json
{
    "taskId": 4,
    "project": "Project",
    "description": "Description",
    "times": [
        {
            "start": "YYYY-MM-DD HH:MM:SS",
            "end": "YYYY-MM-DD HH:MM:SS"
        }
    ]
}
```
