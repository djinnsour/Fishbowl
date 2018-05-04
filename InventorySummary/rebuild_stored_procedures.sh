#!/bin/bash
#
# We rebuild the Fishbowl Mirror Database database nightly.  This involves dropping the
# database and re-importing it from the most recent backup.  As a result any stored
# procedures in the database are lost.  This script simply re-adds them every morning.
#
mysql -h sqlserver -uusername -ppassword fishbowlmirrordatabase < /home/bw/scripts/storedprocedures/invsumtablebuild2.storedprocedure.sql
mysql -h sqlserver -uusername -ppassword fishbowlmirrordatabase < /home/bw/scripts/storedprocedures/invsumtablebuild1.storedprocedure.sql
mysql -h sqlserver -uusername -ppassword fishbowlmirrordatabase < /home/bw/scripts/storedprocedures/invsumtablebuild3.storedprocedure.sql
