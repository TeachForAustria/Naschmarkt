#!/usr/bin/python
import cgi
import urllib2
import uuid
from ftplib import FTP

import MySQLdb
import re

TAG_RE = re.compile(r'<[^>]+>')

def remove_tags(text):
    return TAG_RE.sub('', text)

dbOld = MySQLdb.connect(
    host="mysql003.tophosting.at",      # host ip
    user="dbue88a3f4",                  # username
    passwd="Khy?8k9p",                  # password
    db="dbf26d5267"                     # name of the database
)

dbNew = MySQLdb.connect(
    host="127.0.0.1",                   # host ip
    port=33060,                         # port
    user="homestead",                   # username
    passwd="secret",                    # password
    db="homestead"                      # name of the database
)

# Create a Cursor object
# It executes all the queries
cur = dbOld.cursor()

# select all posts with the type post
cur.execute(
    "SELECT id, post_title, post_content, post_date FROM wp_posts WHERE post_type='post' && post_status='publish'")

insertCursor = dbNew.cursor()

# 
for row in cur.fetchall():
    id = row[0]
    title = row[1]
    description = remove_tags(row[2])
    date = row[3]

    print date

    try:
        insertCursor.execute(
            "INSERT INTO posts (name, description, owner_id, created_at, updated_at) VALUES ('{0}', '{1}', 1, '{2}', '{2}')".format(
                title, description, date))
        dbNew.commit()
    except:
        print "Error inserting posts into the Database"
        dbNew.rollback()

    post_id = insertCursor.lastrowid

    if post_id > 0:
        print str(post_id) + "  " + title

        cur.execute(
            "SELECT term.name FROM (wp_terms AS term INNER JOIN wp_term_taxonomy AS tax ON term.term_id = tax.term_id) INNER JOIN wp_term_relationships AS relation ON relation.term_taxonomy_id = tax.term_taxonomy_id WHERE relation.object_id = {0}".format(
                id))

        print "    TAGS"
        for row in cur.fetchall():
            tag = row[0]

            print "    " + tag

            insertCursor.execute(
                "SELECT EXISTS(SELECT * FROM tags where value = '{0}')".format(
                    tag))


            if insertCursor.fetchall()[0][0] == 1:
                insertCursor.execute(
                    "SELECT id FROM tags WHERE value = '{0}'".format(
                        tag))
                tag_id = insertCursor.fetchall()[0][0]

                insertCursor.execute("INSERT IGNORE INTO post_tag (post_id, tag_id) VALUES ('{0}', '{1}')".format(post_id, tag_id))
            else:
                try:
                    insertCursor.execute(
                        "INSERT INTO tags (value) VALUES ('{0}')".format(
                            tag))
                    dbNew.commit()
                    tag_id = insertCursor.lastrowid
                    insertCursor.execute(
                        "INSERT INTO post_tag (post_id, tag_id) VALUES ('{0}', '{1}')".format(
                            post_id, tag_id))
                except:
                    print "Error inserting tags into the Database"
                    dbNew.rollback()




    # Use all the SQL you like
    cur.execute("SELECT guid FROM wp_posts WHERE post_type=\"attachment\" && guid != ' ' && post_parent = {0}".format(id))

    files = []

    print "    ATTACHMENT URLS"
    # print all the first cell of all the rows
    for row in cur.fetchall():
        try:
            attachmentURL = row[0]

            print "    " + attachmentURL

            filename = uuid.uuid4()


            ftp = FTP('192.168.10.10')
            ftp.login('vagrant', 'vagrant')

            ftp.cwd('naschmarkt')
            ftp.cwd('storage')
            ftp.cwd('app')

            response = urllib2.urlopen(attachmentURL)

            originalFilename = urllib2.unquote(response.geturl()).split('/')[-1]

            extension = originalFilename.split('.')[-1]

            downloaded_file = open("tmp", "w")
            downloaded_file.write(response.read())
            downloaded_file.close()

            with open('tmp', 'rb') as ftpup:
                ftp.storbinary('STOR ' + str(filename) + '.' + extension, ftpup)

            ftp.quit()

            try:
                insertCursor.execute(
                    "INSERT INTO documents (post_id, name, created_at, updated_at) VALUES ('{0}', '{1}', '{2}', '{2}')".format(
                        post_id, originalFilename, date))
                dbNew.commit()
            except:
                print "Error inserting documents into the Database"
                dbNew.rollback()

            document_id = insertCursor.lastrowid

            try:
                insertCursor.execute(
                    "INSERT INTO document_versions (document_id, created_at, updated_at, uuid, extension) VALUES ('{0}', '{1}', '{1}', '{2}', '{3}')".format(
                        document_id, date, str(filename), extension))
                dbNew.commit()
            except:
                print "Error inserting document versions into the Database"
                dbNew.rollback()
        except urllib2.HTTPError:
            print 'Could not download file'



dbOld.close()
dbNew.close()
