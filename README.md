# Queue demo

1. copy `config/.env.default` to `config/.env` and configure queue and database vars
2. `composer install`
3. `bin/cake migrations migrate --plugin=Josegonzalez/CakeQueuesadilla`
4. `bin/cake worker`

Now that the worker is running, you can demo the queue manager calling the event via the Ping shell:

```
$ bin/cake ping
$ bin/cake ping with words
```
