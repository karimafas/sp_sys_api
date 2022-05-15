## Backup the database
pg_dump -h surus.db.elephantsql.com -U rxdytnjy rxdytnjy -Fc > /Users/karimafas/Developer/misc/sp_sys/spsys.dump

## Restore the database
pg_restore -h rogue.db.elephantsql.com -d uqszhzhs -U uqszhzhs /Users/karimafas/Developer/misc/sp_sys/spsys.dump