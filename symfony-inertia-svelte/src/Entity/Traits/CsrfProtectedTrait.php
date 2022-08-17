<?php

namespace App\Entity\Traits;

use Symfony\Component\Serializer\Annotation\Groups;

trait CsrfProtectedTrait
{
    #[Groups(['csrf'])]
    public ?string $csrfToken = null;
}
