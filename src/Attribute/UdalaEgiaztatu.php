<?php

namespace App\Attribute;

use Attribute;
    
#[Attribute(Attribute::TARGET_CLASS)]
class UdalaEgiaztatu
{
    public function __construct(
        public string $userFieldName,
    ) {}

    
}
