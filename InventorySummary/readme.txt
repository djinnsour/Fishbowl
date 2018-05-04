Some things to understand:

1.  We keep a mirror of the Fishbowl environment up and running on another MySQL server.  The mirror is rebuilt nightly from the Fishbowl backup.

2.  I like to crazy MySQL queries that should probably be done in ECMAScript (Old name for Javascript before Oracle decided to do what Oracle does best - sue people). So, some of my MySQL queries are overly complicated.  And I've found breaking the complicated script into small temporary tables first, then running a final query against that is much faster than one huge query with a ton of joins.

3.  The location groups, ids, names, etc. are specific to our site.  You would need to determine the correct numbers for your installation.  Essentially whichever one is "Stock" is your StockIn location and "Ship" is your StockOut location.  If something is received it will always go into some Stock location.  If it is sold it will always exit a Ship location.

4.  The warehouse department initially ran the Fishbowl system. They were not concerned with things like cost or auditing changes. So a lot of the parts ended up having zero cost. A lot of parts magically appear or disappear with no reference purchase order, sales order, or other documentation.  To deal with this the report may do some weird things with cost that were requested by the accounting department.


So, with that in mind, this is a series of stored procedures used to produce a report of the inventory summary for a period of time.  The inventory summary is specific to the requirements of our accounting department so it may be different from your needs.  If so, the code may at least get you started.


Files - 

invsumtablebuild1.storedprocedure.sql (INVSUMTABLEBUILD1)
---------------------------------------------------------

 This is a stored procedure used by index.php.  This calls the INVSUMTABLEBUILD2 stored procedure with the locationgroup id, begin location id, end location id, begin date and end date. It does it for all of the locations we have setup in Fishbowl.  It essentially runs a query, dumps the results to a temporary table, then queries that table for the final report.

invsumtablebuild1.storedprocedure.sql (INVSUMTABLEBUILD2)
---------------------------------------------------------

 This is a stored procedure used by INVSUMTABLEBUILD1 and INVSUMTABLEBUILD3. It creates the main temporary table if it does not already exist.  Then it drops a few temporary tables and recreates them for usage by this stored procedure.

 temporary table tmp_openingbal - This is a table showing the inventory totals at the end of the day before 
                                  the date range. 
 temporary table tmp_endingbal -  This is a table showing the inventory totals at the end of the last day of
                                  the date range.
 
Once that is done we run the main query which adds the information to the main temporary table.  The query is pretty self explanitory. The only weird part you may notice is the creation of a join called GETPROD.  Essentially we have some parts with multiple Product names - old product names we're keeping for reference, and new product names that went into effect last year.  If we don't exclude the old product names the report will have duplicates.  So, find the product(s) that belong to a part.id, then I get the max product.id from that list. It looks recursive, but it works.

I can extrapolate on the query if anyone is interested.  But, if you look at the field names I assigned to the results it will probably make sense.

invsumtablebuild3.storedprocedure.sql (INVSUMTABLEBUILD3)
---------------------------------------------------------

 This is the same as invsumtablebuild1.storedprocedure.sql. The only difference is it excludes results that are all zero. 

rebuild_stored_procedures.sh
----------------------------
 As noted before the Fishbowl database mirror is rebuilt nightly.  So, this is run as a cron job to readd the stored procedures.

menuheader.html
---------------
 The main script uses an include so the menu is shared across the site.  This was included for reference only and isn't necessary for the rest of the report.

index.php
---------
 This is the main page used for the report. It should be self explanitory.