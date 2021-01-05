<?php

namespace SendCloud\SendCloudV2\Model\ResourceModel\Carrier\Servicepointrate;

class DataHashGenerator
{
    /**
     * @param array $data
     * @return string
     */
    public function getHash(array $data)
    {
        $countryId = $data['dest_country_id'];
        $regionId = $data['dest_region_id'];
        $zipCode = $data['dest_zip'];
        $conditionValue = $data['condition_value'];

        return sprintf("%s-%d-%s-%F", $countryId, $regionId, $zipCode, $conditionValue);
    }
}
