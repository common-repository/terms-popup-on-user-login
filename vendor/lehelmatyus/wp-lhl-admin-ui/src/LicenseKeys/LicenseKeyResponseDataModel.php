<?php

namespace WpLHLAdminUi\LicenseKeys;

class LicenseKeyResponseDataModel {
    public $id;
    public $productId;
    public $licenseKey;
    public $createdAt;
    public $createdBy;
    public $expiresAt;
    public $source;
    public $status;
    public $timesActivated;
    public $timesActivatedMax;
    public $updatedAt;
    public $updatedBy;
    public $userId;
    public $validFor;

    function __construct($id, $productId, $licenseKey, $createdAt, $createdBy, $expiresAt, $source, $status, $timesActivated, $timesActivatedMax, $updatedAt, $updatedBy, $userId, $validFor) {
        $this->id = $id;
        $this->productId = $productId;
        $this->licenseKey = $licenseKey;
        $this->createdAt = $createdAt;
        $this->createdBy = $createdBy;
        $this->expiresAt = $expiresAt;
        $this->source = $source;
        $this->status = $status;
        $this->timesActivated = $timesActivated;
        $this->timesActivatedMax = $timesActivatedMax;
        $this->updatedAt = $updatedAt;
        $this->updatedBy = $updatedBy;
        $this->userId = $userId;
        $this->validFor = $validFor;
    }

    public function getId() {
        return $this->id;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function getLicenseKey() {
        return $this->licenseKey;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getCreatedBy() {
        return $this->createdBy;
    }

    public function getExpiresAt() {
        return $this->expiresAt;
    }

    public function getSource() {
        return $this->source;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getTimesActivated() {
        return $this->timesActivated;
    }

    public function getTimesActivatedMax() {
        return $this->timesActivatedMax;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function getUpdatedBy() {
        return $this->updatedBy;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getValidFor() {
        return $this->validFor;
    }
}
