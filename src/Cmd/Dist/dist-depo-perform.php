<?php
require_once '../vendor/autoload.php';
use Armaulan\DmsTelegram\Class\Database;
use Armaulan\DmsTelegram\Class\Telegram;

$tlgrm = new Telegram('BOT_RTPDM');

$db = new Database();
try {
    $db->getConnection("DISTRIBUTOR");
} catch (PDOException $e) {
    echo "Connection DB Issue";
    exit();
}   

$sql ="select 
    d.depo_name
    , date_format(t.invoice_date, '%Y-%m') yearmonth
    , 'INV' as remarks
    , format(sum(t.total_tagihan),0) as amt
    from tagihan t
    join depo d on d.depo_id = t.depo_id 
    where 0=0
    -- and t.depo_id = 650
    and d.depo_code = '$messageSecond'
    and t.invoice_date >= DATE_FORMAT(DATE_SUB(CURRENT_DATE(), interval 2 MONTH), '%Y-%m') 
    group by yearmonth
    UNION
        select
        d.depo_name
        , date_format(po.selling_date, '%Y-%m') yearmonth
        , 'PO' as remarks
        , format(sum(po.total_amount),0) as amt
        from purchase_order po 
        join depo d on d.depo_id = po.depo_id 
        where 0=0
        -- and po.depo_id = 650
        and d.depo_code = '$messageSecond'
        and po.selling_date >= DATE_FORMAT(DATE_SUB(CURRENT_DATE(), interval 2 MONTH), '%Y-%m') 
        and po.sent_sap = 1
        group by yearmonth";

try {
    $data = $db->getDistributorData($sql);
} catch (Exception $e) {
    echo "OK: Database Issue";
    exit();
}

$msg = "";

if(!empty($data)){ 
    foreach ($data as $row) {
        if($msg == "") {
            $msg .= $row['depo_name']. "\n\n";
        }

        $msg .= $row['yearmonth']. " ". $row['remarks']. " ". $row['amt']. "\n";
    }

    $tlgrm->sendMessage($sender, $msg);
    echo "OK !";
    exit();
}
