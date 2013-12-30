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

    function susun() {
        $this->db->where('akses',0);
        $data = $this->db->get('proses');
        if ($data->num_rows > 0) {
            return $data->result();
        } else {
            return $data = array();
        }
    }

    function outbox($params) {
        $this->db->insert('outbox', $params);
    }

    function updateProses($params) {
        $this->db->where('id', $params);
        $this->db->update('proses', array('akses' => 1));
    }

}

?>
