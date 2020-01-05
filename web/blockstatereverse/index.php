<?php

declare(strict_types=1);

//Needed to validate tags
$json_Tags = json_decode(file_get_contents("data/possible_block_states.json"), true);
//Needed
$json_Mapping = json_decode(file_get_contents("data/runtime_block_states_1.13.json"), true);
$blockData = strtolower(str_replace("minecraft:", "", $_GET["blockData"] ?? "air"));
#var_dump($blockData);
#print "<hr>";
$re = '/([\w:]+)(?:\[([\w=,]*)\])?/m';
preg_match_all($re, $blockData, $matches, PREG_SET_ORDER, 0);
// Print the entire match result
#var_dump($matches);
print "<h3>Input</h3>";

$selectedBlockName = $matches[0][1] ?? "air";
$extraData = $matches[0][2] ?? "";
#var_dump($selectedBlockName);
#print "<hr>";
$explode = explode(",", $extraData);
#print "<hr>";
$get_data = [];
foreach ($explode as $boom) {
    #print $boom;
    if (strpos($boom, "=") === false) continue;
    #var_dump(__LINE__, explode("=", $boom));
    [$k, $v] = explode("=", $boom);
    //VALIDATE HERE
    $type = $json_Tags[$k]["type"]??"string";
    #print $type;
    if($type === "string") $get_data[$k] = strval($v);
    if ($type === "integer") $get_data[$k] = intval($v);
    if ($type === "boolean") $get_data[$k] =  boolval($v);
}
var_dump($get_data);print "<hr>";
print_r($get_data);print "<hr>";
unset($_GET["blockData"]);
?>
    <form target="_self" action="index.php" method="get">
        <?php
        print "<input type='text' value='$blockData' style='width:100%;' placeholder='Enter a block string' name='blockData'><hr>"; ?>
        <button type="submit" style="width: 100%;left:0;right: 0;height: 3em;">Show!</button>
    </form>
    <h3>Matched blocks</h3>
<ul>
<?php
$json_Mapping = array_filter($json_Mapping, function (array $data, int $runtimeId) use ($selectedBlockName, $get_data): bool {
    //name check
    $name = str_replace("minecraft:", "", $data["name"]);
    if ($name !== $selectedBlockName) return false;
    //parameter check
    $get_data = array_map('strtolower', $get_data);#var_dump($get_data);
    if (array_key_exists("states", $data)) {
       # var_dump($data);
        foreach ($get_data as $key => $value) {
            #var_dump($key,$value);
            $dataValue = $data["states"][$key]??[];
            if (array_key_exists("val", $dataValue) && $dataValue["val"] != $value) {
                return false;
            }
        }
    } else return false;
    return true;
}, ARRAY_FILTER_USE_BOTH);
print "<li>".print_r(json_encode($json_Mapping),true)."</li>";
?>
</ul>