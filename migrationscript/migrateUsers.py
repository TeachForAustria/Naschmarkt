#!/usr/bin/python
# -*- coding: utf-8 -*-

import random
import string
import MySQLdb

import config


def id_generator():
    return ''.join(random.choice(string.ascii_lowercase + string.digits) for _ in range(100))


dbOld = MySQLdb.connect(
    host=config.dbOld['host'],  # host ip
    port=config.dbOld['port'],  # port
    user=config.dbOld['user'],  # username
    passwd=config.dbOld['password'],  # password
    db=config.dbOld['database']  # name of the database
)

dbNew = MySQLdb.connect(
    host=config.dbNew['host'],  # host ip
    port=config.dbNew['port'],  # port
    user=config.dbNew['user'],  # username
    passwd=config.dbNew['password'],  # password
    db=config.dbNew['database']  # name of the database
)

oldDatabaseCursor = dbOld.cursor()
newDatabaseCursor = dbNew.cursor()

oldDatabaseCursor.execute('SELECT ID, display_name, user_email, user_registered FROM wp_users')

file_id = open('user_id', 'w')
file_user_csv = open('users.csv', 'w')
file_user_csv.write('Name;Email;Aktivierungs Link\n')

for user_row in oldDatabaseCursor.fetchall():
    old_id = user_row[0]
    name = user_row[1]
    email = user_row[2]
    register_date = user_row[3]
    activation_token = id_generator()

    print(name + "  " + email + "  " + str(register_date))

    try:
        newDatabaseCursor.execute(
            "INSERT INTO users (name, email, created_at, updated_at, activation_token, is_staff, password) VALUES ('{0}', '{1}', '{2}', '{2}', '{3}', 0, '' )".format(
                name, email, register_date, activation_token))
        dbNew.commit()
    except Exception as e:
        print("Error inserting user into the Database " + str(e))
        dbNew.rollback()

    user_id = newDatabaseCursor.lastrowid

    file_id.write(str(old_id) + '=' + str(user_id) + '\n')
    file_user_csv.write(
        name + ';' + email + ';' + 'http://der-naschmarkt.at/activate?token=' + str(activation_token) + '&id=' + str(
            user_id) + '\n')
