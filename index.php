<?php
require_once 'lib/simple_html_dom.php';
header('Content-Type: application/json');

$json           = array();
$base_url       = 'https://www.centraldoseventos.com.br';
$url_event      = $base_url . '/events?query=divinopolis';
$html_list      = file_get_html($url_event);
$events_list    = $html_list->find('.holdInfEvento');

foreach ($events_list as $item) {
    $event_url = $item->find('a', 0)->href;
    if ($event_url != '') {        
        get_event_details($base_url . $event_url);
    }
}

function get_event_details($url) {
    $html_details = file_get_html($url);
    $event_detail = $html_details->find('#evento');

    foreach ($event_detail as $item) {
        $event = array(
            'status'        => 1,
            'title'         => $item->find('.titulo-event h1', 0)->innertext,
            'url_image'     => $item->find('.img-event img', 0)->src,
            'place'         => $item->find('.box-adress .adress li span', 1)->innertext,
            'place_phone'   => '',
            'date'          => $item->find('.box-adress .date span', 0)->innertext . ' - ' . $item->find('.box-adress .date span', 1)->innertext,
            'address'       => $item->find('.box-adress .adress li span', 0)->innertext,
            'city'          => $item->find('.box-adress .adress li span', 1)->innertext,
            'id_category'   => 1,
            'id_sale_place' => 1
        );
        $json[] = $event;
    }

    echo json_encode($json, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}