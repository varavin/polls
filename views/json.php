<?php
/**
 * @var object $object - object to be converted to JSON
 */
?>
<?= json_encode(compact('success', 'message', 'data')) ?>
