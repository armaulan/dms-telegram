<?php
require_once '../vendor/autoload.php';

use Armaulan\DmsTelegram\Class\Database;
use Armaulan\DmsTelegram\Class\Telegram;

#echo $messageSecond;
$tlgrm = new Telegram('BOT_RTPDM');

$query = "
    select 
    aod.order_number 
    , d.depo_name 
    , aod.order_date 
    , aod.delivery_date 
    , if(aod.is_cashless = 0, 'COD', 'CASHLESS') payment_type
    , if(aod.is_active = 1, 'APPROVED', 'NOT ACTIVED') as payment_status
    , aod.nama_penerima
    , aod.no_telp 
    , CONCAT(REPLACE(LEFT(aod.no_telp , 1), '0', 'wa.me/62'), SUBSTRING(aod.no_telp , 2, CHAR_LENGTH(aod.no_telp))) as phone
    , CONCAT(REPLACE(LEFT(aoc.no_telp , 1), '0', 'wa.me/62'), SUBSTRING(aoc.no_telp , 2, CHAR_LENGTH(aoc.no_telp))) as phone2
    , aos.order_status_name 
    , format(aod.total_cbp, 0) as total_cbp
    , aod.alamat_pengiriman
    , aod.total_qty 
    , aod.total_product 
    , aod.notes
    , aod.created
    , aod.voucher
    , aod.modified 
    , ul.name 
    , ul.username
    , rp.photo_path
    , aot.order_tipe_name 
    , format(IFNULL(aod.delivery_fee, 0), 0) as delivery_fee 
    , format((aod.total_cbp + IFNULL(aod.delivery_fee, 0)), 0) as grand_total
    , apt.payment_type_name
    from agent_order_data aod
    left join agent_order_status aos on aos.order_status_id = aod.order_status_id 
    left join depo d on d.depo_id = aod.depo_id 
    left join user_login ul on ul.user_id = aod.assign_to
    left join agent_order_selling aos2 on aos2.order_number = aod.order_number 
    left join report_photo rp on rp.report_id = aos2.selling_number 
    left join agent_order_customer aoc on aoc.customer_id = aod.customer_id
    left join agent_order_tipe aot on aot.order_tipe_id = aod.order_tipe_id 
    left join agent_payment_type apt on apt.payment_type_id = aod.payment_type_id 
    where 0=0
    and aod.order_number like '%$messageSecond'
    limit 5
";

$query2 = "
       select aodd.order_id
       , p.short_name 
       , FORMAT(aodd.cbp, 0) as cbp 
       , aodd.qty 
       , FORMAT(aodd.total_cbp, 0) as total_cbp
       from agent_order_data_detail aodd
       left join agent_order_data aod on aod.order_id = aodd.order_id
       left join product p on p.product_id = aodd.product_id 
       where 0=0
       and aod.order_number like '%$messageSecond'
       limit 25
";

$db = new Database();
try {
    $db->getConnection("AGENT");
    $data = $db->getAgentData($query);
} catch (PDOException $e) {
    $tlgrm->sendMessage('-1001638586770', "Internal DB Error");
    echo "Connection DB Issue";
    exit();
}

if(!empty($data)){
    try {
        $data2 = $db->getAgentData($query2);
    } catch (PDOException $e) {
        $tlgrm->sendMessage('-1001638586770', "Internal DB Error");
        echo "Connection DB Issue";
        exit();
    }
    
    $txt = "";
    foreach ($data as $row) {
        $txt .=  $row['order_number']. "\n" 
        . "Status : " . $row['order_status_name'] . " | ". $row['modified'] . "\n\n"
        . "Nama : " . $row['nama_penerima'] . "\n"
        . "Depo : " . $row['depo_name'] . "\n"
        . "Source : " . $row['order_tipe_name'] . "\n"
        . "Tgl Order : " . $row['order_date'] . "\n" 
        . "Tgl Kirim : " . $row['delivery_date'] . "\n" 
        . "Chabot to DMS : " . $row['created'] . "\n"
        . "Payment Type : " . $row['payment_type'] . " - ". $row['payment_type_name'] . "\n"
        . "Payment Status : " . $row['payment_status'] . "\n"
        . "Phone Pemesan : " . $row['phone2'] . "\n" 
        . "Phone Penerima : " . $row['phone'] . "\n" 
        . "Voucher : " . $row['voucher'] . "\n"
        . "Photo Kirim : " . $row['photo_path'] . "\n"
        . "PIC : " . $row['name'] . " (" . $row['username'] . ") \n\n"
        . "Addr : " . $row['alamat_pengiriman'] . "\n\n" 
        . "Note : " . $row['notes'] . "\n\n" 
        . "Total Qty : " . $row['total_qty'] . "\n"
        . "Total CBP : " . $row['total_cbp'] . "\n"
        . "Delivery Fee : " . $row['delivery_fee'] . "\n"
        . "Grand Total : " . $row['grand_total'] . "\n\n";
    }

    foreach ($data2 as $row) {
        $txt .= $row['short_name'] . " | @" 
        . $row['cbp'] . " | "
        . $row['qty'] . "Pcs | "
        . $row['total_cbp'] . " \n";
    }

    $tlgrm->sendMessage('-1001638586770', $txt);
    echo "OK !";
            
} else {
    $tlgrm->sendMessage('-1001638586770', "$messageSecond  - Order is not found !");
    echo "Order is not found !";
    exit();
}
