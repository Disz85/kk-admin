<?php

namespace App\Rules;

use App\Interfaces\HasDependencies;
use Illuminate\Contracts\Validation\Rule;

class CheckDependencies implements Rule
{
    public function __construct(protected HasDependencies $dependable)
    {
    }

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value): bool
    {
        /* @var HasDependencies $this->dependable */
        return ! $this->dependable->hasDependencies();
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'Resource cannot be deleted due to existence of related resources.';
    }
}
