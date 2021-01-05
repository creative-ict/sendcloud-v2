<?php

namespace SendCloud\SendCloudV2\Model\ResourceModel\Carrier\Servicepointrate;

use Magento\Framework\DB\Select;
use Magento\Quote\Model\Quote\Address\RateRequest;

/**
 * Query builder for table rate
 */
class RateQuery
{
    /**
     * @var RateRequest
     */
    private $request;

    /**
     * RateQuery constructor.
     * @param RateRequest $request
     */
    public function __construct(
        RateRequest $request
    ) {
        $this->request = $request;
    }

    /**
     * Prepare select
     *
     * @param Select $select
     * @return Select
     */
    public function prepareSelect(Select $select)
    {
        $select->where(
            'website_id = :website_id'
        )->order(
            ['dest_country_id DESC', 'dest_region_id DESC', 'dest_zip DESC', 'condition_value DESC']
        )->limit(
            1
        );

        // Render destination condition
        $orWhere = '(' . implode(
            ') OR (',
            [
                "dest_country_id = :country_id AND dest_region_id = :region_id AND dest_zip = :postcode",
                "dest_country_id = :country_id AND dest_region_id = :region_id AND dest_zip = :postcode_prefix",
                "dest_country_id = :country_id AND dest_region_id = :region_id AND dest_zip = ''",

                // Handle asterisk in dest_zip field
                "dest_country_id = :country_id AND dest_region_id = :region_id AND dest_zip = '*'",
                "dest_country_id = :country_id AND dest_region_id = 0 AND dest_zip = '*'",
                "dest_country_id = '0' AND dest_region_id = :region_id AND dest_zip = '*'",
                "dest_country_id = '0' AND dest_region_id = 0 AND dest_zip = '*'",
                "dest_country_id = :country_id AND dest_region_id = 0 AND dest_zip = ''",
                "dest_country_id = :country_id AND dest_region_id = 0 AND dest_zip = :postcode",
                "dest_country_id = :country_id AND dest_region_id = 0 AND dest_zip = :postcode_prefix"
            ]
        ) . ')';
        $select->where($orWhere);

        // Render condition by condition name
        if (is_array($this->request->getSenConditionName())) {
            $orWhere = [];
            foreach (range(0, count($this->request->getSenConditionName())) as $conditionNumber) {
                $bindNameKey = sprintf(':sen_condition_name_%d', $conditionNumber);
                $bindValueKey = sprintf(':condition_value_%d', $conditionNumber);
                $orWhere[] = "(sen_condition_name = {$bindNameKey} AND condition_value <= {$bindValueKey})";
            }

            if ($orWhere) {
                $select->where(implode(' OR ', $orWhere));
            }
        } else {
            $select->where('sen_condition_name = :sen_condition_name');
            $select->where('condition_value <= :condition_value');
        }

        return $select;
    }

    /**
     * Returns query bindings
     *
     * @return array
     */
    public function getBindings()
    {
        $bind = [
            ':website_id' => (int)$this->request->getWebsiteId(),
            ':country_id' => $this->request->getDestCountryId(),
            ':region_id' => (int)$this->request->getDestRegionId(),
            ':postcode' => $this->request->getDestPostcode(),
            ':postcode_prefix' => $this->getDestPostcodePrefix()
        ];

        // Render condition by condition name
        if (is_array($this->request->getSenConditionName())) {
            $i = 0;
            foreach ($this->request->getSenConditionName() as $conditionName) {
                $bindNameKey = sprintf(':sen_condition_name_%d', $i);
                $bindValueKey = sprintf(':condition_value_%d', $i);
                $bind[$bindNameKey] = $conditionName;
                $bind[$bindValueKey] = $this->request->getData($conditionName);
                $i++;
            }
        } else {
            $bind[':sen_condition_name'] = $this->request->getSenConditionName();
            $bind[':condition_value'] = round($this->request->getData($this->request->getSenConditionName()), 4);
        }

        return $bind;
    }

    /**
     * Returns rate request
     *
     * @return RateRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns the entire postcode if it contains no dash or the part of it prior to the dash in the other case
     *
     * @return string
     */
    private function getDestPostcodePrefix()
    {
        if (!preg_match("/^(.+)-(.+)$/", $this->request->getDestPostcode(), $zipParts)) {
            return $this->request->getDestPostcode();
        }

        return $zipParts[1];
    }
}
