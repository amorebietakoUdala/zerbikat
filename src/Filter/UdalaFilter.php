<?php

namespace App\Filter;

use App\Attribute\UdalaEgiaztatu;
use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

class UdalaFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        $reflectionClass = $targetEntity->getReflectionClass();

        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "user aware" (marked with an annotation)
        $udalaEgiaztatu = $reflectionClass->getAttributes(UdalaEgiaztatu::class);

        if (count($udalaEgiaztatu) === 0) {
            return '';
        }
        $fieldName = $udalaEgiaztatu[0]->getArguments()['userFieldName'];
        try {
            // Don't worry, getParameter automatically quotes parameters
            $udalaId = $this->getParameter('udala_id');
        } catch (\InvalidArgumentException) {
            // No user id has been defined
            return '';
        }
        if (empty($fieldName) || ($udalaId=="'138'")) {
            return '';
        }

        $query = sprintf('%s.%s = %s', $targetTableAlias, $fieldName, $udalaId);

        return $query;  
    }
}