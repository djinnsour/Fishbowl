use fishbowlmirrordatabase;
drop procedure if exists INVSUMTABLEBUILD2;
DELIMITER $$
CREATE PROCEDURE INVSUMTABLEBUILD2 (
IN groupid INT,
IN stockloc INT,
IN shiploc INT,
IN begindate DATE,
IN enddate DATE)
BEGIN
CREATE TEMPORARY TABLE IF NOT EXISTS invsumtablebuild (
ID int NOT NULL AUTO_INCREMENT,
LocationId int(20)NOT NULL,
Location varchar(30) DEFAULT NULL,
Partid int(11) NOT NULL,
Partnumber varchar(70) DEFAULT NULL,
Productnumber varchar(70) DEFAULT NULL,
Description varchar(32) DEFAULT NULL,
UOM varchar(10) DEFAULT NULL,
OpenQty decimal(20,0) DEFAULT NULL,
OpenCost decimal(23,2) DEFAULT NULL,
OpenTotal decimal(23,2) DEFAULT NULL,
InQty decimal(20,0) DEFAULT NULL,
InAvgCost decimal(23,2) DEFAULT NULL,
InTotal decimal(23,2) DEFAULT NULL,
OutQty decimal(20,0) DEFAULT NULL,
OutAvgCost decimal(23,2) DEFAULT NULL,
OutTotal decimal(23,2) DEFAULT NULL,
EndQty decimal(20,0) DEFAULT NULL,
EndCost decimal(23,2) DEFAULT NULL,
EndTotal decimal(23,2) DEFAULT NULL,
PRIMARY KEY (ID)
);
drop table if exists tmp_openingbal;
create temporary table tmp_openingbal(
select openingbal.partId as PARTID, openingbal.qtyonhand as QTYONHAND, CASE WHEN openingbal.qtyonhand < 0 THEN 0 ELSE openingbal.cost END as COST, openingbal.dateCreated from inventorylog openingbal inner join (SELECT partId, MAX(dateCreated) as MAXDATE from inventorylog WHERE locationGroupId=groupid AND DATE_SUB(dateCreated, interval 1 day) < begindate group by partId) ds1 on ds1.partId=openingbal.partId and openingbal.dateCreated=ds1.MAXDATE where openingbal.locationGroupId=groupid order by openingbal.partId);
drop table if exists tmp_endingbal;
create temporary table tmp_endingbal(
select endingbal.partId as PARTID, endingbal.qtyonhand as QTYONHAND, CASE WHEN endingbal.qtyonhand < 0 THEN 0 ELSE endingbal.cost END as COST, endingbal.dateCreated from inventorylog endingbal inner join (SELECT partId, MAX(dateCreated) as MAXDATE from inventorylog WHERE locationGroupId=groupid AND dateCreated < DATE_ADD(enddate, interval 1 day) group by partId) ds1 on ds1.partId=endingbal.partId and endingbal.dateCreated=ds1.MAXDATE where endingbal.locationGroupId=groupid order by endingbal.partId);
INSERT INTO invsumtablebuild(LocationId, Location, Partid, Partnumber, Productnumber, Description, UOM, OpenQTY, OpenCost, OpenTotal, InQty, InAvgCost, InTotal, OutQty, OutAvgCost, OutTotal, EndQty, EndCost, EndTotal)
select groupid as LocationId,
CASE
 WHEN locationgroup.id = 1 THEN 'Main'
 WHEN locationgroup.id = 2 THEN 'WHS2'
 WHEN locationgroup.id = 3 THEN 'RMA'
 WHEN locationgroup.id = 4 THEN 'WHS3'
 WHEN locationgroup.id = 5 THEN 'WHS4'
 WHEN locationgroup.id = 6 THEN 'WHS5'
 WHEN locationgroup.id = 7 THEN 'WHS6'
END as Location, 
part.id as Partid,
part.num as Partnumber,
COALESCE(GETPROD.PRODNUM,'--------') as Productnumber,
CASE
 WHEN GETPROD.PRODDESC IS NOT NULL THEN SUBSTRING(GETPROD.PRODDESC,1,32)
 ELSE SUBSTRING(part.description,1,32)
END AS Description,
uom.code as UOM,
COALESCE(ROUND(openingbal.OPENQTY,0),0) as OpenQTY,
COALESCE(ROUND(openingbal.OPENCOST,2),0) as OpenCost,
ROUND(((COALESCE(ROUND(openingbal.OPENQTY,0),0)) * (COALESCE(ROUND(openingbal.OPENCOST,3),0))),2) as OpenTotal,
COALESCE(ROUND(STOCKIN1.QTY,0),0) as InQty,
COALESCE(ROUND(STOCKIN1.COST,2),0) as InAvgCost,
ROUND(((COALESCE(ROUND(STOCKIN1.QTY,0),0)) * (COALESCE(ROUND(STOCKIN1.COST,3),0))),2) as InTotal,
COALESCE(ROUND(STOCKOUT1.QTY,0),0) as OutQty,
COALESCE(ROUND(STOCKOUT1.COST,2),0) as OutAvgCost,
ROUND(((COALESCE(ROUND(STOCKOUT1.QTY,0),0)) * (COALESCE(ROUND(STOCKOUT1.COST,3),0))),2) as OutTotal,
COALESCE(ROUND(endingbal.ENDQTY,0),0) as EndQty,
COALESCE(ROUND(endingbal.ENDCOST,2),0) as EndCost,
ROUND((COALESCE(ROUND(endingbal.ENDQTY,0),0)) * (COALESCE(ROUND(endingbal.ENDCOST,3),0)),2) as EndTotal
from part
inner join (select PARTID as ENDPARTID, QTYONHAND as ENDQTY, COST ENDCOST from tmp_endingbal) endingbal on endingbal.ENDPARTID=part.id
left join (select PARTID as OPENPARTID, QTYONHAND as OPENQTY, COST as OPENCOST from tmp_openingbal) openingbal on openingbal.OPENPARTID=part.id
left join (select products1.partId as PRODPART, products1.num as PRODNUM, products1.description as PRODDESC, products1.id as PRODID FROM product products1 inner join (select partId, MAX(id) as MAXPROD from product group by partId) UNIQPROD on UNIQPROD.partId=products1.partId and products1.id=UNIQPROD.MAXPROD) GETPROD on GETPROD.PRODPART=part.id
left join (select id, name from locationgroup) locationgroup on locationgroup.id=groupid
left join (select partId, SUM(changeQty) as QTY, AVG(cost) as COST from inventorylog where DATE(dateCreated) between begindate and enddate and locationGroupId=groupid and begLocationId=shiploc and endLocationId=shiploc group by partId) STOCKOUT1 on STOCKOUT1.partId=part.id
left join (select partId, SUM(changeQty) as QTY, AVG(cost) as COST from inventorylog where DATE(dateCreated) between begindate and enddate and locationGroupId=groupid and endLocationId=stockloc group by partId) STOCKIN1 on STOCKIN1.partId=part.id
left join (select id, code from uom) uom on uom.id=part.uomId
order by part.num;
END$$

