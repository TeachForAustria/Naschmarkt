"""
This is the config file for the Migration
There are 3 things to configure.
    - the old Database to migrate from
    - the new Database to save the migration
    - FTP connection to save the files
"""

# Old Database.
# This is where the Data is taken from
dbOld = {
    'host':         "",		# host ip
    'port':         0,		# port
    'user':         "",         # username
    'password':     "",		# password
    'database':     ""		# name of the database
}

# New Database.
# This is where the Data will be stored
dbNew = {
    'host':         "",		# host ip
    'port':         0,		# port
    'user':         "",		# username
    'password':     "",		# password
    'database':     ""		# name of the database
}

# FTP connection to save the files
ftpConnection = {
    'host':         "",		# host ip
    'user':         "",		# username
    'password':     "",		# password
    'directory':    ""		# directory where to save the files to
}

# Every post with these tags will not be migrated.
# e.g. ['TFAktuell', 'AMS']
remove_tags = []
