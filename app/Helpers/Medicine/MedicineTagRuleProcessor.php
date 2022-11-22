<?php

namespace App\Helpers\Medicine;

use App\Models\MedicineTagRule;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MedicineTagRuleProcessor
{
    /**
     * Applies the rules to the given parameters
     * Returns tags from matching rules
     * @param array $input
     * @return Collection
     */
    public function process(array $input): Collection
    {
        $params = $this->lowerCaseInput($input);
        $result = collect();
        $rules = MedicineTagRule::with('tags')->get();

        foreach ($rules as $rule) {
            $key = match ($rule->type) {
                MedicineTagRule::TYPE_URL => 'url',
                MedicineTagRule::TYPE_ATC_NAME => 'atc_name',
                MedicineTagRule::TYPE_ACTIVE_SUBSTANCE => 'active_substance',
                MedicineTagRule::TYPE_DOCUMENTATION => 'documentation',
                default => null,
            };

            if ($key && ! empty($params[$key])) {
                if (Str::contains($params[$key], Str::lower($rule->filter))) {
                    $result = $result->merge($rule->tags);
                }
            }
        }

        return $result->unique('id');
    }

    /**
     * Returns a copy of the input array with every element lower cased.
     * @param array $input
     * @return array
     */
    private function lowerCaseInput(array $input): array
    {
        $result = [];

        foreach ($input as $key => $item) {
            $result[$key] = $item !== null ? Str::lower($item) : null;
        }

        return $result;
    }
}
