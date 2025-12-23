<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class Contact extends BaseResource
{
    /**
     * The type of the contact (sales, support, etc.).
     *
     * @var string
     */
    public $type;

    /**
     * The name of the contact (individual or organisational unit).
     *
     * @var string
     */
    public $name;

    /**
     * The public telephone number of the contact.
     *
     * @var string
     */
    public $phoneNumber;

    /**
     * The public email address of the contact.
     *
     * @var string
     */
    public $email;
}
