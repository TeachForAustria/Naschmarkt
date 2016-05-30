#!/usr/bin/python
# -*- coding: utf-8 -*-
import os
import urllib2
import uuid
from ftplib import FTP
import MySQLdb
import re
import time
import textract
import shutil

import config

start_time = time.time()
if not os.path.exists('tmp'):
    os.mkdir('tmp')

dbOld = MySQLdb.connect(
    host=config.dbOld['host'],          # host ip
    port=config.dbOld['port'],          # port
    user=config.dbOld['user'],          # username
    passwd=config.dbOld['password'],    # password
    db=config.dbOld['database']         # name of the database
)

dbNew = MySQLdb.connect(
    host=config.dbNew['host'],          # host ip
    port=config.dbNew['port'],          # port
    user=config.dbNew['user'],          # username
    passwd=config.dbNew['password'],    # password
    db=config.dbNew['database']         # name of the database
)

# Create a Cursor object
# It executes all the queries
oldDatabaseCursor = dbOld.cursor()

# select all posts with the type post
oldDatabaseCursor.execute(
    "SELECT id, post_title, post_content, post_date FROM wp_posts WHERE post_type='post' && post_status='publish'")

newDatabaseCursor = dbNew.cursor()

# Loop through the resulting posts
for post_row in oldDatabaseCursor.fetchall():
    old_post_id = post_row[0]
    title = post_row[1]
    description = post_row[2]
    date = post_row[3]

    try:
        newDatabaseCursor.execute(
            "INSERT INTO posts (name, description, owner_id, created_at, updated_at) VALUES ('{0}', '{1}', 1, '{2}', '{2}')".format(
                title, description, date))
        dbNew.commit()
    except:
        print "Error inserting posts into the Database"
        dbNew.rollback()

    post_id = newDatabaseCursor.lastrowid

    if post_id > 0:
        print str(post_id) + "  " + title

        # Query for Getting tags from the wordpress database
        oldDatabaseCursor.execute(
            "SELECT term.name FROM (wp_terms AS term INNER JOIN wp_term_taxonomy AS tax ON term.term_id = tax.term_id) INNER JOIN wp_term_relationships AS relation ON relation.term_taxonomy_id = tax.term_taxonomy_id WHERE relation.object_id = {0}".format(
                old_post_id))

        print "    TAGS"

        # save tags into database
        for tag_row in oldDatabaseCursor.fetchall():
            tag = tag_row[0]

            # delete leading _
            if tag[0] is '_':
                tag = tag[1:]

            print "    " + tag

            # Check if tag is already in databank
            newDatabaseCursor.execute(
                "SELECT EXISTS(SELECT * FROM tags where value = '{0}')".format(
                    tag))

            if newDatabaseCursor.fetchall()[0][0] == 1:
                # if it already exists select it's id
                newDatabaseCursor.execute(
                    "SELECT id FROM tags WHERE value = '{0}'".format(
                        tag))
                tag_id = newDatabaseCursor.fetchall()[0][0]

                newDatabaseCursor.execute(
                    "SELECT EXISTS(SELECT * FROM post_tag where post_id = '{0}' && tag_id = '{1}')".format(
                        post_id, tag_id))

                if newDatabaseCursor.fetchall()[0][0] != 1:
                    # And insert relationship between post and tag
                    newDatabaseCursor.execute(
                        "INSERT IGNORE INTO post_tag (post_id, tag_id) VALUES ('{0}', '{1}')".format(
                            post_id, tag_id))
            else:
                try:
                    # if it doesn't exist insert it into the dtabase
                    newDatabaseCursor.execute(
                        "INSERT INTO tags (value) VALUES ('{0}')".format(
                            tag))
                    dbNew.commit()
                    tag_id = newDatabaseCursor.lastrowid

                    # And define the post_tag relation
                    newDatabaseCursor.execute(
                        "INSERT INTO post_tag (post_id, tag_id) VALUES ('{0}', '{1}')".format(
                            post_id, tag_id))
                except:
                    print '    Error inserting tags into the Database'
                    dbNew.rollback()

    # get the attachment urls from the post
    oldDatabaseCursor.execute(
        "SELECT guid FROM wp_posts WHERE post_type='attachment' && guid != ' ' && post_parent = {0}".format(
            old_post_id))

    # Connect to FTP server for uploading the files
    ftp = FTP(config.ftpConnection['host'])
    ftp.login(config.ftpConnection['user'], config.ftpConnection['password'])

    # Change directory to ~/naschmarkt/storage/app/
    for directory in config.ftpConnection['directory'].split('/'):
        ftp.cwd(directory)

    # print all the first cell of all the rows
    for url_row in oldDatabaseCursor.fetchall():
        attachmentURL = url_row[0]

        # Generate uuid for the filename and the database
        filename = uuid.uuid4()

        # Sometimes attachment_id has to be replaced with download
        # This is done because attachment_id is a webpage showing the document and download is a download link.
        # They share the same id
        if '?attachment_id=' in attachmentURL:
            attachmentURL = attachmentURL.replace('attachment_id', 'download')

        try:
            # Download file from url
            response = urllib2.urlopen(attachmentURL)
        except urllib2.HTTPError, err:
            print '    ' + str(err.code) + ' file not found on ' + err.url
            continue

        # Print the URL. If a redirect occurred it prints both urls
        if attachmentURL is response.geturl():
            print "    " + attachmentURL
        else:
            print "    " + attachmentURL + " -> " + response.geturl()

        # Save the Original filename
        originalFilename = urllib2.unquote(response.geturl()).split('/')[-1]

        # and extension
        extension = originalFilename.split('.')[-1]

        file_to_upload = open('tmp/tmp_file_to_upload.' + extension, 'w')
        file_to_upload.write(response.read())
        file_to_upload.close()

        try:
            text = textract.process("tmp/tmp_file_to_upload." + extension)
        except:
            print '    Document unreadable'

        # Upload file to FTP Server
        with open('tmp/tmp_file_to_upload.' + extension, 'rb') as ftpup:
            ftp.storbinary('STOR ' + str(filename) + '.' + extension, ftpup)

        # Try inserting the document
        try:
            newDatabaseCursor.execute(
                "INSERT INTO documents (post_id, name, created_at, updated_at) VALUES ('{0}', '{1}', '{2}', '{2}')".format(
                    post_id, originalFilename, date))
            dbNew.commit()
        except:
            print '    Error inserting documents into the Database'
            dbNew.rollback()

        document_id = newDatabaseCursor.lastrowid

        try:
            newDatabaseCursor.execute(
                "INSERT INTO document_versions (document_id, created_at, updated_at, uuid, extension) VALUES ('{0}', '{1}', '{1}', '{2}', '{3}')".format(
                    document_id, date, str(filename), extension))
            dbNew.commit()
        except:
            print '    Error inserting document versions into the Database'
            dbNew.rollback()

        # Insert keywords logic
        if 'text' in locals():
            for keyword in text.split():
                if keyword is not '[pic]':
                    keyword = re.sub('[^A-Za-z0-9ßäöüÄÖÜ]', '', keyword.lower())

                    newDatabaseCursor.execute(
                        "SELECT EXISTS(SELECT * FROM keywords where value = '{0}')".format(
                            keyword))

                    if newDatabaseCursor.fetchall()[0][0] == 1:
                        # if it already exists select it's id
                        newDatabaseCursor.execute(
                            "SELECT id FROM keywords WHERE value = '{0}'".format(
                                keyword))
                        keyword_id = newDatabaseCursor.fetchall()[0][0]

                        newDatabaseCursor.execute(
                            "SELECT EXISTS(SELECT * FROM document_keyword where document_id = '{0}' && keyword_id = '{1}')".format(
                                document_id, keyword_id))

                        if newDatabaseCursor.fetchall()[0][0] != 1:
                            # And insert relationship between document and keyword
                            newDatabaseCursor.execute(
                                "INSERT IGNORE INTO document_keyword (document_id, keyword_id) VALUES ('{0}', '{1}')".format(
                                    document_id, keyword_id))
                    else:
                        try:
                            # if it doesn't exist insert it into the dtabase
                            newDatabaseCursor.execute(
                                "INSERT INTO keywords (value) VALUES ('{0}')".format(
                                    keyword))
                            dbNew.commit()
                            keyword_id = newDatabaseCursor.lastrowid

                            newDatabaseCursor.execute(
                                "SELECT EXISTS(SELECT * FROM document_keyword where document_id = '{0}' && keyword_id = '{1}')".format(
                                    document_id, keyword_id))

                            if newDatabaseCursor.fetchall()[0][0] != 1:
                                # And define the document_keyword relation
                                newDatabaseCursor.execute(
                                    "INSERT IGNORE INTO document_keyword (document_id, keyword_id) VALUES ('{0}', '{1}')".format(
                                        document_id, keyword_id))
                        except:
                            print '    Error inserting keywords into the Database'
                            dbNew.rollback()

    ftp.quit()

if os.path.exists('tmp'):
    shutil.rmtree('tmp')

dbOld.close()
dbNew.close()

print("--- %s seconds ---" % (time.time() - start_time))
