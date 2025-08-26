<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Sts\V20150401\Models;

use AlibabaCloud\Tea\Model;

class GetCallerIdentityResponseBody extends Model
{
    /**
     * @description The ID of the Alibaba Cloud account to which the current requester belongs.
     *
     * @example 196813200012****
     *
     * @var string
     */
    public $accountId;

    /**
     * @description The Alibaba Cloud Resource Name (ARN) of the current requester.
     *
     * @example acs:ram::196813200012****:user/admin
     *
     * @var string
     */
    public $arn;

    /**
     * @description The type of the identity. Valid values:
     *
     * - AssumedRoleUser: a RAM role
     * @example RAMUser
     *
     * @var string
     */
    public $identityType;

    /**
     * @description The ID of the principal.
     *
     * @example 28877424437521****
     *
     * @var string
     */
    public $principalId;

    /**
     * @description The ID of the request.
     *
     * @example 3C87BF47-3724-5443-ADC1-5AEAD9A03EB1
     *
     * @var string
     */
    public $requestId;

    /**
     * @description The ID of the RAM role.
     *
     * > This parameter is returned only when the current requester uses a RAM role.
     * @example 33537620082992****
     *
     * @var string
     */
    public $roleId;

    /**
     * @description The ID of the current requester.
     *
     * > This parameter is returned only when the current requester uses an Alibaba Cloud account or a RAM user.
     * @example 216959339000****
     *
     * @var string
     */
    public $userId;
    protected $_name = [
        'accountId'    => 'AccountId',
        'arn'          => 'Arn',
        'identityType' => 'IdentityType',
        'principalId'  => 'PrincipalId',
        'requestId'    => 'RequestId',
        'roleId'       => 'RoleId',
        'userId'       => 'UserId',
    ];

    public function validate()
    {
    }

    public function toMap()
    {
        $res = [];
        if (null !== $this->accountId) {
            $res['AccountId'] = $this->accountId;
        }
        if (null !== $this->arn) {
            $res['Arn'] = $this->arn;
        }
        if (null !== $this->identityType) {
            $res['IdentityType'] = $this->identityType;
        }
        if (null !== $this->principalId) {
            $res['PrincipalId'] = $this->principalId;
        }
        if (null !== $this->requestId) {
            $res['RequestId'] = $this->requestId;
        }
        if (null !== $this->roleId) {
            $res['RoleId'] = $this->roleId;
        }
        if (null !== $this->userId) {
            $res['UserId'] = $this->userId;
        }

        return $res;
    }

    /**
     * @param array $map
     *
     * @return GetCallerIdentityResponseBody
     */
    public static function fromMap($map = [])
    {
        $model = new self();
        if (isset($map['AccountId'])) {
            $model->accountId = $map['AccountId'];
        }
        if (isset($map['Arn'])) {
            $model->arn = $map['Arn'];
        }
        if (isset($map['IdentityType'])) {
            $model->identityType = $map['IdentityType'];
        }
        if (isset($map['PrincipalId'])) {
            $model->principalId = $map['PrincipalId'];
        }
        if (isset($map['RequestId'])) {
            $model->requestId = $map['RequestId'];
        }
        if (isset($map['RoleId'])) {
            $model->roleId = $map['RoleId'];
        }
        if (isset($map['UserId'])) {
            $model->userId = $map['UserId'];
        }

        return $model;
    }
}
