# Mobile Web Specialist Kejar 2018 - Group 2

`Laporin` Aplikasi pelaporan jalan rusak di Kota Malang. 

## Collaborator
--

## Require
Laravel Passport

## Reponse
Code
```
200 - OK!
400 - Bad Request
500 - Internal Server Error
```
Sample: Response Bad Request
```
{
    "status": 400,
    "errorMessage": "The credentials you entered did not match our records.",
}
```
Sample: Response Success
```
{
    "status": 200,
    "id": 1,
    "name": "Eko Triono"
}
```

## User
POST /user/register
URL: http://domain/api/user/register

POST /user/login
URL: http://domain/api/user/login

GET /user/me.json
URL: http://domain/api/user/me.json