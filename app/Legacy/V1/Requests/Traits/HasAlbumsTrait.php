<?php

/**
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2017-2018 Tobias Reich
 * Copyright (c) 2018-2025 LycheeOrg.
 */

namespace App\Legacy\V1\Requests\Traits;

use Illuminate\Support\Collection;

/**
 * @template T
 */
trait HasAlbumsTrait
{
	/**
	 * @var Collection<int,T>
	 */
	protected Collection $albums;

	/**
	 * @return Collection<int,T>
	 */
	public function albums(): Collection
	{
		return $this->albums;
	}
}
