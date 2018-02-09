select num as 'Order Number',
customerPO as 'Purchase Order',
salesman as Salesperson,
billToName as Customer,
totalPrice as 'Order Total',
DATE_FORMAT(dateCompleted, '%Y-%m-%d') as Fulfilled,
carrier.name as Carrier,
shipcarton.trackingNum as 'Tracking ID'
from so
left join (select orderId, carrierId, trackingNum from shipcarton) shipcarton on shipcarton.orderId=so.id
left join (select id, name from carrier) carrier on carrier.id=shipcarton.carrierId
where statusId=60 and (DATE(dateCompleted)=CURDATE() or DATE(dateCompleted)=DATE(NOW() - INTERVAL 1 DAY))
order by so.id;
