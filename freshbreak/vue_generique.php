<?php
class Vue_generique {
    public function __construct() {
        ob_start();
    }

    public function close_buffer() {
        return ob_get_clean();
    }
}
