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
Register
POST /user/register
URL: http://domain/api/user/register

Login
POST /user/login
URL: http://domain/api/user/login

Get Info
GET /user/me.json
URL: http://domain/api/user/me.json

Edit Info
PATCH /user/edit
URL: http://domain/api/user/edit

##  Report

Get All report
GET /report.json
URL: http://domain/api/report.json

Get My report
GET /report/me.json
URL: http://domain/api/report/me.json

Detail Report
GET /report/{id}
URL: http://domain/api/report/1

Create Report
POST /report{id}
URL: http://domain/api/report/{id}

Update Report
PATCH /report/{id}
URL: http://domain/api/report/{id}

Delete Report
DELETE /report/{id}
URL: http://domain/api/report/{id}

##  Image

GET /image/report
URL: http://domain/image/report/namaFile.png

GET /image/user
URL: http://domain/image/user/namaFile.png

POST /image/report
URL: http://domain/api/image/report

POST /image/user
URL: http://domain/api/image/user

POST /image/me
URL: http://domain/api/image/me