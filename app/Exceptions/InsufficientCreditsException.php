<?php

namespace App\Exceptions;

use Exception;

/**
 * Thrown when a company tries to submit a job for review but has zero job posting credits
 * remaining. Controllers catch this and redirect to the pricing page rather than letting it
 * bubble up as a 500 — running out of credits is an expected, recoverable business state,
 * not a bug.
 */
class InsufficientCreditsException extends Exception
{
    //
}
