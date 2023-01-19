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
    public function passes($attribute, $value)
    {
        /* @var HasDependencies $this->dependable */
        return ! $this->dependable->hasDependencies();
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return 'Resource cannot be deleted due to existence of related resources.';
    }
}
