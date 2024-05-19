<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="access_logs")
 */
class AccessLogs
{
    /**
     * @MongoDB\Id(type="string")
     */
    private $id;

    /**
     * @MongoDB\Field(type="integer", name="contact_id", nullable=true)
     * @var int | null
     */
    private $contactId;

    /**
     * @MongoDB\Field(type="integer", name="user_id", nullable=true)
     * @var int | null
     */
    private $userId;

    /**
     * @MongoDB\Field(type="string", name="api_route", nullable=true)
     * @var string|null
     */
    private $apiRoute;

    /**
     * @MongoDB\Field(type="string", name="user_ip_address", nullable=true)
     * @var string|null
     */
    private $userIpAddress;

    /**
     * @MongoDB\Field(type="string", name="fe_route", nullable=true)
     * @var string|null
     */
    private $feRoute;

    /**
     * @MongoDB\Field(type="string", name="method", nullable=true)
     * @var string|null
     */
    private $method;

    /**
     * @MongoDB\Field(type="hash", name="request_payload", nullable=true)
     *
     */
    private $requestData;

    /**
     * @MongoDB\Field(type="hash", name="response", nullable=true)
     * @var object|null
     */
    private $response;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(name="created_at", type="date", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime| null
     *
     * @MongoDB\Field(name="updated_at", type="date", nullable=true)
     */
    private $updatedAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getContactId(): ?int
    {
        return $this->contactId;
    }

    public function setContactId(?int $contactId): void
    {
        $this->contactId = $contactId;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    public function getApiRoute(): ?string
    {
        return $this->apiRoute;
    }

    public function setApiRoute(?string $apiRoute): void
    {
        $this->apiRoute = $apiRoute;
    }

    public function getUserIpAddress(): ?string
    {
        return $this->userIpAddress;
    }

    public function setUserIpAddress(?string $userIpAddress): void
    {
        $this->userIpAddress = $userIpAddress;
    }

    public function getFeRoute(): ?string
    {
        return $this->feRoute;
    }

    public function setFeRoute(?string $feRoute): void
    {
        $this->feRoute = $feRoute;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): void
    {
        $this->method = $method;
    }
    public function getRequestData(): ?object
    {
        return $this->requestData;
    }

    public function setRequestData($requestData): void
    {
        $this->requestData = $requestData;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response): void
    {
        $this->response = $response;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}