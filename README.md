symfonytest
===========

A Symfony project created on November 20, 2017, 8:56 pm.


Usage
------

Run the web server:
```sh
$ php bin/console server:run
```

Register a new user:
```
$ curl -X POST http://127.0.0.1:8000/register -d _username=bill -d _password=test
-> User johndoe successfully created
```

Get a JWT token:
```
$ curl -X POST http://127.0.0.1:8000/api/login_check -d _username=bill -d _password=test
-> { "token": "[TOKEN]" }  
```

Access a secured route:
```
$ curl -H "Authorization: Bearer [TOKEN]" http://127.0.0.1:8000/api
-> Logged in as bill
```



