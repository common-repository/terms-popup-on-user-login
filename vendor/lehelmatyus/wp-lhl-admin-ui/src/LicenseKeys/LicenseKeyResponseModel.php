<?php

namespace WpLHLAdminUi\LicenseKeys;

class LicenseKeyResponseModel {
    public $success;
    public LicenseKeyResponseDataModel $data;


    function __construct($success, $data) {
        $this->success = $success;
        $this->data = $data;
    }

    public function getSuccess() {
        return $this->success;
    }
    public function getData() {
        return $this->data;
    }
}
