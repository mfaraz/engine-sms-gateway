<?php

/*
 * Author : Andi Kurniawan
 * Follow me : @nakamichikun
 */

class Auto extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('modauto');
    }

    function index() { // function untuk mengambil data dari datacenter
        $this->xmlrpc->server(DTCEN);
        $this->xmlrpc->method('pesan');
        $data = array('ruly', do_hash('rumahlinux'));
        $this->xmlrpc->set_debug(FALSE);
        $this->xmlrpc->request($data);
        $this->xmlrpc->send_request();
        $respon = json_decode($this->xmlrpc->display_response());
        if ($respon[1] != NULL) {
            for ($i = 0; $i < count($respon); $i++) {
                $input = array(
                    'id' => $respon[1][$i]->id,
                    'nop' => $respon[1][$i]->nop,
                    'nama' => $respon[1][$i]->nama,
                    'telp' => $respon[1][$i]->telp,
                    'tahun' => $respon[1][$i]->tahun,
                    'nominal' => $respon[1][$i]->nominal,
                    'pesan' => $respon[1][$i]->pesan,
                    'akses' => $respon[1][$i]->akses,
                    'user' => $respon[1][$i]->user
                );
                $this->modauto->insertPesan($input);
                $ws_log = array(
                    'id_ws' => $respon[1][$i]->id,
                    'code' => $respon[0]->code,
                    'message' => $respon[0]->message
                );
                $this->modauto->insertLog($ws_log);
                $da = $this->modauto->susun();
                foreach ($da as $dt) {
                    $message = 'DISPENDA Tulungagung mengucapkan Terimakasih Kpd Yth. ' . $dt->nama . ', pemilik NOP ' . $dt->nop . ', atas pembayaran PBB tahun ' . $dt->tahun . ' sejumlah Rp ' . $dt->nominal;
                    $insert = array(
                        'DestinationNumber' => $dt->telp,
                        'TextDecoded' => $message,
                        'SenderID' => 'Merah',
                        'CreatorID' => $dt->user,
                    );
                    $this->modauto->outbox($insert);
                    $this->modauto->updateProses($dt->id);
                    $var = array(
                        'idProses' => $dt->id,
                        'data' => json_encode($insert)
                    );
                    $this->modauto->insertLogOutbox($var);
                }
            }
        } else {
            $ws_log = array(
                'code' => $respon[0]->code,
                'message' => $respon[0]->message
            );
            $this->modauto->insertLog($ws_log);
        }
    }

    function test() { // function untuk mengirim data dari oracle ke vps
        $this->xmlrpc->server(DTCEN);
        $this->xmlrpc->method('test');
        $data = array('ruly', do_hash('rumahlinux'));
        $this->xmlrpc->set_debug(FALSE);
        $this->xmlrpc->request($data);
        $this->xmlrpc->send_request();
        $respon = json_decode($this->xmlrpc->display_response());
        echo '<pre>';
        print_r($respon);
        echo '</pre>';
    }

}

?>
