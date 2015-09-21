# Time Manager

## API Endpoints

| URL           | Method | Input         | Description    |
|---------------|--------|---------------|----------------|
| /api/config   | GET    | Config Object | Get config     |
| /api/config   | PUT    | Config Object | Update config  |
| /api/mail     | POST   | -             | Send mail      |
| /api/mail     | GET    | -             | Get mail text  |
| /api/task     | POST   | Task Object   | Add a new task |
| /api/task/:id | GET    | -             | Get a task     |
| /api/task/:id | PUT    | Task Object   | Update task    |
| /api/task/:id | DELETE | -             | Delete task    |

## API Objects

#### Config

```json
{
    "mail": {
        "from": "send.from@mail.com",
        "to": ["send.to@mail.com"],
        "subject": "Subject",
        "body": "Body"
    }
}
```

#### Task

```json
{
    "project": "Project",
    "description": "Description",
    "time": [
        {
            "from": "YYYY-MM-DD HH:MM",
            "to": "YYYY-MM-DD HH:MM"
        }
    ]
}
```
