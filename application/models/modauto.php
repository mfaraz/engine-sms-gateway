<?php

/*
 * Author : Andi Kurniawan
 * Follow me : @nakamichikun
 */

class Modauto extends CI_Model {

    function insertPesan($param) {
        $this->db->insert('proses', $param);
    }

    function insertLog($param) {
        $this->db->insert('ws_log', $param);
    }

}

?>
