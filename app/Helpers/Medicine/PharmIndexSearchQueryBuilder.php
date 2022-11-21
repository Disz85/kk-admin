<?php

namespace App\Helpers\Medicine;

class PharmIndexSearchQueryBuilder
{
    /**
     * Builds the query for the search Api endpoint from the incoming conditions
     * @param array $conditions
     * @return array
     */
    public function buildSearchQuery(array $conditions): array
    {
        $query = [];

        $this->addPagingToSearchQuery($conditions, $query);
        $this->addSearchTermToSearchQuery($conditions, $query);
        $this->addAtcCodeFilterToSearchQuery($conditions, $query);
        $this->addProductFiltersToSearchQuery($conditions, $query);
        $this->addAvailabilityFiltersToSearchQuery($conditions, $query);
        $this->addSupportPercentageFilterToSearchQuery($conditions, $query);
        $this->addPriceRangeFilterToSearchQuery($conditions, $query);

        return $query;
    }

    private function addPagingToSearchQuery(array $conditions, array &$query): void
    {
        $limit = $conditions['per_page'] ?? config('medicine.defaults.pagination.per_page');
        $page = $conditions['page'] ?? 1;
        $offset = ($page - 1) * $limit;

        $query['limit'] = $limit;
        $query['offset'] = $offset;
    }

    private function addSearchTermToSearchQuery(array $conditions, array &$query): void
    {
        $search_type = $conditions['search_type'] ?? null;

        switch ($search_type) {
            case 'nevben':
                $query['queryString'] = $conditions['search'];
                break;
            case 'hatoanyagban':
                $query['moleculeName'] = $conditions['search'];
                break;
            case 'mindenhol':
                $query['queryString'] = $conditions['search'];
                $query['moleculeName'] = $conditions['search'];
                break;
        }
    }

    private function addAtcCodeFilterToSearchQuery(array $conditions, array &$query): void
    {
        $atc = $conditions['atc'] ?? null;

        if ($atc !== null) {
            $query['atcCode'] = $atc;
        }
    }

    private function addProductFiltersToSearchQuery(array $conditions, array &$query): void
    {
        $product_type_ids = $conditions['product_type_ids'] ?? [];

        if (!empty($product_type_ids)) {
            $query['typeIds'] = $product_type_ids;
        }
    }

    private function addAvailabilityFiltersToSearchQuery(array $conditions, array &$query): void
    {
        $types = $conditions['types'] ?? [];

        $prescription_codes = [];

        if (in_array('v', $types)) {
            $prescription_codes[] = 'V';
        }

        if (in_array('vn', $types)) {
            $prescription_codes[] = 'VN';
        }

        if (!empty($prescription_codes)) {
            $query['presc'] = implode(',', $prescription_codes);
        }

        if (in_array('pk', $types)) {
            $query['infoIcon'] = ['out_pharma'];
        }
    }

    private function addSupportPercentageFilterToSearchQuery(array $conditions, array &$query): void
    {
        $support_percentage_code = $conditions['support_percentage_code'] ?? null;

        if ($support_percentage_code !== null) {
            $query['codePercent'] = $support_percentage_code;
        }
    }

    private function addPriceRangeFilterToSearchQuery(array $conditions, array &$query): void
    {
        $price_from = $conditions['price_from'] ?? null;
        $price_to = $conditions['price_to'] ?? null;

        if ($price_from !== null) {
            $query['priceFrom'] = $price_from;
        }

        if ($price_to !== null) {
            $query['priceTo'] = $price_to;
        }
    }
}
