<?php 
require(__DIR__."/get_tax_typeahead.php.conf");

if (!isset($db) || $db === false || !defined("__TAX_TABLE__")) {
    echo json_encode([]);
    exit(1);
}


$search_type = filter_input(INPUT_GET, "t", FILTER_SANITIZE_STRING);
$query = filter_input(INPUT_GET, "q", FILTER_SANITIZE_STRING);
$category = filter_input(INPUT_GET, "c", FILTER_SANITIZE_STRING);


if (!$search_type || !$query || !$category) {
    echo json_encode([]);
    exit(1);
}


if ($search_type !== "tax-auto") {
    echo json_encode([]);
    exit(1);
}


$data_array = get_data($db, __TAX_TABLE__, $query, $category);

echo json_encode($data_array);






function get_data($db, $table, $query, $cat) {
    $field = strtolower($cat);
    if ($field == "superkingdom")
        $field = "domain";
    else if ($field == "order")
        $field = "taxorder";

    $query = "$query%";

    $sql = "SELECT DISTINCT $field FROM $table WHERE $field LIKE :query LIMIT 100";
    $results = $db->query($sql, array(":query" => $query));

    $data = array();
    for ($i = 0; $i < count($results); $i++) {
        array_push($data, $results[$i][$field]);
    }
    return $data;
}



