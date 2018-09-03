## Petronetto's MessageBird Task Assessment

### How run the application

1. Ensure that you have *Composer*, *Docker* and *Docker Compose* properly installed.

2. Run `./up.sh`, and the command will ask your MessageBird API KEY, paste in terminal and wait some seconds while Compose install the dependencies and Docker build the cointainer. Maybe this is a good time to get a coffee ☕️.

3. If all worked as expected (I'm praying for this), go to [http://localhost:8080](http://localhost:8080) and you should see the home page.

### The application

The appliation has 5 routes:

| Method | Route      | What                                                                                                  |
|--------|------------|-------------------------------------------------------------------------------------------------------|
| GET    | [/](http://localhost:8080/)                | Is a Home page, nothing special here 😅                               |
| GET    | [sms](http://localhost:8080/sms)           | Form to create a new message                                          |
| POST   | sms/create                                 | Endpoint where new messa is processed                                 |
| GET    | [sms/list](http://localhost:8080/sms/list) | A simple table that returns the list of messages                      |
| GET    | sms/:id                                    | Return the details of a given ID as JSON                              |
| GET    | [coverage](http://localhost:8080/coverage) | Show the code coverrage, you must run `composer test-coverage` before |


### How it works?

I tried simulate a [Queue Based Load Leveling pattern](https://docs.microsoft.com/en-us/azure/architecture/patterns/queue-based-load-leveling), as the task describe to don't use third party packages, I used only the PHP.

Basicaly, when you create a new message, the service layer will call an event and put this object in a queue, running in background is the `worker`, that is responsible to process this queue and send the messages. The worker have 1 sec delay, so, doesn't matter (in theory at least) how many request are sent, all will be queued and processed one by one after 1 sec.

![Queue Based Load Leveling pattern](https://docs.microsoft.com/en-us/azure/architecture/patterns/_images/queue-based-load-leveling-worker-role.png)


### How could be improved?

There's a lot of things that could be improved, for exemple: a better error handler, a better way to render the templates and also, handle properly with the income request, etc...

### Final considerations...

I started this this assesment with a TDD approach, but because my week was bery busy, I need finish it with all necessary tests, I know that maybe I deserve be putted in a jail forereve, but I ask you to forgive me this time 😬

BTW, also please forgive my layout, css and those things... I know that are pretty ridiculous 💩

So, that's it, *May the git push --force be with you*! 🖖🏻