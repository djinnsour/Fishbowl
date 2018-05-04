use fishbowlmirrordatabase;
drop procedure if exists INVSUMTABLEBUILD3;
DELIMITER $$
CREATE PROCEDURE INVSUMTABLEBUILD3 (
IN begindate DATE,
IN enddate DATE)
BEGIN
/* Main Stock and Shipping */
call INVSUMTABLEBUILD2(1,1,2,begindate,enddate);
/* Amazon Stock and Shipping */
call INVSUMTABLEBUILD2(2,5,7,begindate,enddate);
/* RMA Stock and Shipping */
call INVSUMTABLEBUILD2(3,11,13,begindate,enddate);
/* JMA Stock and Shipping */
call INVSUMTABLEBUILD2(4,18,20,begindate,enddate);
/* EPA Stock and Shipping */
call INVSUMTABLEBUILD2(5,24,26,begindate,enddate);
/* WAI Stock and Shipping */
call INVSUMTABLEBUILD2(6,28,30,begindate,enddate);
/* FRA Stock and Shipping */
call INVSUMTABLEBUILD2(7,33,35,begindate,enddate);
select * from invsumtablebuild where ((ABS(OpenQty)+ABS(InQty)+ABS(OutQty)+ABS(EndQty)) > 0);
END$$
