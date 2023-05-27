<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use App\Helpers\ResponseHelper;

class CustomValidationException extends Exception
{
    /**
     * The validator instance.
     *
     * @var Validator
     */
    public Validator $validator;

    /**
     * The status code to use for the response.
     *
     * @var int
     */
    public int $status = 422;

    /**
     * Create a new exception instance.
     *
     * @param  Validator  $validator
     * @return void
     */
    public function __construct($validator)
    {
        parent::__construct('The given data was invalid.');

        $this->validator = $validator;
    }

    public function render(): JsonResponse
    {
        return ResponseHelper::apiResponse(false, implode('<br>', $this->validator->errors()->all()) , null, null, $this->status);
    }
}
