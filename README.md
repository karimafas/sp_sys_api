## Backup the database

pg_dump -h [host] -U [user] [db_name] -Fc > [path]

## Restore the database

pg_restore -h [host] -d [db_name] -U [user] [path]
