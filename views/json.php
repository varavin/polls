<?php
/**
 * @var boolean $success - success flag
 * @var string $message - status message
 * @var object $data - object to be converted to JSON
 */
echo json_encode(compact('success', 'message', 'data'));
