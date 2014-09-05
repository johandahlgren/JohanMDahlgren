<?php
$value = getValueFromString("Type", $data);
if ($value == 431) {
    $containerClass = "center";
} else {
    $containerClass = "normal";
}
?>
<div class="<?php print $containerClass?>">
    <?php renderEntities($id) ?>
</div>