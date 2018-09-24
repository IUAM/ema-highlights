#!/bin/bash

# In Homestead, you may need to run this command using sudo

# TODO: Abstract this script to accept these as arguments?
DB_NAME='ema_highlights_old'
DUMPS_SUBDIR='highlights'

# https://stackoverflow.com/questions/59895/getting-the-source-directory-of-a-bash-script-from-within
DIR_BIN="$( readlink -f $(dirname "${BASH_SOURCE[0]}") )"
DIR_DUMPS="${DIR_BIN}/../database/dumps"
DIR_DB="${DIR_DUMPS}/${DUMPS_SUBDIR}"

# Required for expanding aliases
shopt -s expand_aliases

# We'll use this alias as a prefix for issuing MySQL commands
alias mysql_exec="mysql -u homestead -p'secret' -h 0.0.0.0"

# We need this to generate a comma-separated column list
# https://stackoverflow.com/questions/1527049/join-elements-of-an-array
function join_by { local IFS="$1"; shift; echo "$*"; }

if [ ! -d "${DIR_DB}" ]; then
    mkdir "${DIR_DB}"
fi

# Get all of the tables in this database
TABLES=($(mysql_exec -N -e "
    SELECT table_name
    FROM information_schema.tables
    WHERE table_schema='${DB_NAME}';
"))

for TABLE in "${TABLES[@]}"; do

    COLUMNS=($(mysql_exec -N -e "
        SELECT column_name
        FROM information_schema.columns
        WHERE TABLE_SCHEMA = '${DB_NAME}'
        AND TABLE_NAME = '${TABLE}';
    "))

    CSV_HEADER="$(join_by , "${COLUMNS[@]}")"

    # This might require you to modify mysqld.cnf to disable secure-file-priv
    # https://superuser.com/questions/1088512/how-to-disable-secure-file-priv-mysql-ubuntu

    # ERROR 1290 (HY000) at line 2: The MySQL server is running with the --secure-file-priv
    #     option so it cannot execute this statement

    mysql_exec -e "
        SELECT ${CSV_HEADER}
        FROM ${DB_NAME}.${TABLE}
        INTO OUTFILE '/tmp/${TABLE}.csv'
        FIELDS TERMINATED BY ','
        OPTIONALLY ENCLOSED BY '\"'
        LINES TERMINATED BY '\r\n';
    "

    # https://stackoverflow.com/questions/10587615/unix-command-to-prepend-text-to-a-file
    cat <(echo -e "${CSV_HEADER}") "/tmp/${TABLE}.csv" > "${DIR_DB}/${TABLE}.csv"
    rm "/tmp/${TABLE}.csv"

done
