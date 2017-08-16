<?php

namespace Transfers\Transfers\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transfers
 *
 * @ORM\Table(name="transfers_transfers", indexes={@ORM\Index(name="origin_user_id_index", columns={"origin_user_id"}), @ORM\Index(name="target_user_id_index", columns={"target_user_id"}), @ORM\Index(name="gateway_id_index", columns={"gateway_id"}), @ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Transfers extends \Kazist\Table\BaseTable {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="origin_user_id", type="integer", length=11, nullable=false)
     */
    protected $origin_user_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="target_user_id", type="integer", length=11, nullable=false)
     */
    protected $target_user_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", length=11, nullable=false)
     */
    protected $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="gateway_id", type="integer", length=11, nullable=true)
     */
    protected $gateway_id;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=255, nullable=true)
     */
    protected $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    protected $token;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="params", type="text", nullable=true)
     */
    protected $params;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_canceled", type="integer", length=11, nullable=true)
     */
    protected $is_canceled;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=false)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=false)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=false)
     */
    protected $date_modified;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set origin_user_id
     *
     * @param integer $originUserId
     * @return Transfers
     */
    public function setOriginUserId($originUserId) {
        $this->origin_user_id = $originUserId;

        return $this;
    }

    /**
     * Get origin_user_id
     *
     * @return integer 
     */
    public function getOriginUserId() {
        return $this->origin_user_id;
    }

    /**
     * Set target_user_id
     *
     * @param integer $targetUserId
     * @return Transfers
     */
    public function setTargetUserId($targetUserId) {
        $this->target_user_id = $targetUserId;

        return $this;
    }

    /**
     * Get target_user_id
     *
     * @return integer 
     */
    public function getTargetUserId() {
        return $this->target_user_id;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Transfers
     */
    public function setAmount($amount) {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * Set gateway_id
     *
     * @param integer $gatewayId
     * @return Transfers
     */
    public function setGatewayId($gatewayId) {
        $this->gateway_id = $gatewayId;

        return $this;
    }

    /**
     * Get gateway_id
     *
     * @return integer 
     */
    public function getGatewayId() {
        return $this->gateway_id;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return Transfers
     */
    public function setCurrency($currency) {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Transfers
     */
    public function setToken($token) {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Transfers
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set params
     *
     * @param string $params
     * @return Transfers
     */
    public function setParams($params) {
        $this->params = $params;

        return $this;
    }

    /**
     * Get params
     *
     * @return string 
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Set is_canceled
     *
     * @param integer $isCanceled
     * @return Transfers
     */
    public function setIsCanceled($isCanceled) {
        $this->is_canceled = $isCanceled;

        return $this;
    }

    /**
     * Get is_canceled
     *
     * @return integer 
     */
    public function getIsCanceled() {
        return $this->is_canceled;
    }

    /**
     * Get created_by
     *
     * @return integer 
     */
    public function getCreatedBy() {
        return $this->created_by;
    }

    /**
     * Get date_created
     *
     * @return \DateTime 
     */
    public function getDateCreated() {
        return $this->date_created;
    }

    /**
     * Get modified_by
     *
     * @return integer 
     */
    public function getModifiedBy() {
        return $this->modified_by;
    }

    /**
     * Get date_modified
     *
     * @return \DateTime 
     */
    public function getDateModified() {
        return $this->date_modified;
    }

}
