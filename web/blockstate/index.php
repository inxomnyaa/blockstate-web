<?php

declare(strict_types=1);

$mapping = [];
$vanilla_names = [];
$vanilla_ids = [];
$vanilla_states = [];
$all_possible_states = [];

$json = json_decode(file_get_contents("data/possible_block_states.json"), true);
foreach ($json as $block_state_id => $v) {
    $type = $v["type"];
    $pv = $v["values"];
    #print "<ul><li>$block_state_id [$type]<ul>" . "<br>";
    #print "<li>" . implode(", ", $pv) . "</li></ul></ul>";
    $mapping[$block_state_id] = $pv;
    $all_possible_states[] = $block_state_id;
}
$json2 = json_decode(file_get_contents("data/runtime_block_states_1.13.json"), true);

foreach ($json2 as $v1) {
    $id = $v1["id"];
    $meta = $v1["meta"];
    $name = str_replace("minecraft:", "", $v1["name"]);
    $tgs = [];
    foreach (($states = $v1["states"] ?? []) as $v_block_state_id => $dat) {
        $d = $dat["val"];
        $t = $dat["type"];
    }
    $vanilla_names[$id] = $name;
    $vanilla_ids[$name] = $id;
    $vanilla_states[$id] = $states;
}
?>
<form target="_self" action="index.php" method="get">
    <select name="block" id="block">
        <?php
        $selectedBlockName = $_GET["block"] ?? "air";
        $selectedBlockId = intval($vanilla_ids[$selectedBlockName]);
        foreach ($vanilla_names as $id => $vanillaName)
            print "<option value='$vanillaName' " . ($selectedBlockId === $id ? "selected" : "") . ">$vanillaName</option>";
        ?>
    </select>
    <ul>
        <?php
        foreach ($vanilla_states[$selectedBlockId] as $keyweirdjawhdbndf => $vsid) {
            print "<li>$keyweirdjawhdbndf ";
            $type = $json[$keyweirdjawhdbndf]["type"] ?? "string";
            print $type . " ";
            print "<select name='$keyweirdjawhdbndf' id='$selectedBlockId.$keyweirdjawhdbndf'>";
            //possible states of selected block
            foreach ($mapping[$keyweirdjawhdbndf] as $valThatINeed) {
                if ($type === "boolean") {
                    $valThatINeed = ($valThatINeed ? "true" : "false");
                }
                print "<option value='$valThatINeed'>";
                print "$valThatINeed</option>";
            }
            print "</select></li>";
        }

        #print "<option value='$id' " . ($selectedBlockId === $id ? "selected" : "") . ">$vanillaName</option>";
        ?>
    </ul>
    <button type="submit" style="width: 100%;left:0;right: 0;height: 3em;">Show!</button>
</form>
<?php
    print "<input type='text' value='minecraft:$selectedBlockName";
    if (count($_GET) > 1) {
        print "[";
        $y = [];
        foreach ($_GET as $key => $value) {
            if ($key === "block") continue;
            $y[] = "$key=$value";
        }
        print implode(",", $y);
        print "]";
    }
    print "' style='width:100%;'><hr>";
    #print_r($_GET);
    #print_r(array_keys($json));
    #print_r(array_values($json2));
    ?>