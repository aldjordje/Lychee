<?php

/**
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2017-2018 Tobias Reich
 * Copyright (c) 2018-2025 LycheeOrg.
 */

namespace App\Exceptions;

use App\Exceptions\Internal\LycheeDomainException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MassImportException.
 *
 * This exception collects a list of exceptions which may be thrown when
 * a sequence of photos is imported iteratively.
 * In particular, this exception has not only a single previous exception,
 * but may have a sequence of previous exceptions.
 *
 * Returns status code 422 (Unprocessable entity) to an HTTP client.
 */
class MassImportException extends BaseLycheeException
{
	/**
	 * @var \Throwable[]
	 */
	protected array $previousExceptions;

	/**
	 * @param \Throwable[] $list_of_exceptions
	 *
	 * @throws LycheeDomainException
	 */
	public function __construct(array $list_of_exceptions)
	{
		if (count($list_of_exceptions) === 1) {
			$msg = 'A photo could not be imported';
			$prev = reset($list_of_exceptions);
		} elseif (count($list_of_exceptions) > 1) {
			$msg = 'Several photos could not be imported';
			$prev = null;
		} else {
			throw new LycheeDomainException('$listOfExceptions must not be empty');
		}
		parent::__construct(Response::HTTP_UNPROCESSABLE_ENTITY, $msg, $prev);
		$this->previousExceptions = $list_of_exceptions;
	}

	/**
	 * @return \Throwable[]
	 */
	public function previousExceptions(): array
	{
		return $this->previousExceptions;
	}
}