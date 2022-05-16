## Backup the database

pg_dump -h [host] -U [user] [db_name] -Fc > [path]

## Restore the database

pg_restore -h [host] -d [db_name] -U [user] [path]

## To deploy dev API

1. heroku git:remote -a spsys-dev
2. git push heroku <branch>

# To deploy prod API
1. heroku git:remote -a sp-sys
2. git push heroku <branch>