<?php

/**
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2017-2018 Tobias Reich
 * Copyright (c) 2018-2025 LycheeOrg.
 */

declare(strict_types=1);

namespace Tests\Unit\Http\Requests\Album;

use App\Contracts\Http\Requests\RequestAttribute;
use App\Contracts\Models\AbstractAlbum;
use App\Http\Requests\Album\AddAlbumRequest;
use App\Policies\AlbumPolicy;
use App\Rules\RandomIDRule;
use App\Rules\TitleRule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use LycheeVerify\Contract\VerifyInterface;
use Tests\AbstractTestCase;

class AddAlbumRequestTest extends AbstractTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$mockVerify = $this->createMock(VerifyInterface::class);
		App::instance(VerifyInterface::class, $mockVerify); // VerifyInterface is talking to DB & that is not needed for Request classes
	}

	public function testAuthorization()
	{
		Gate::shouldReceive('check')
			->with(AlbumPolicy::CAN_EDIT, [AbstractAlbum::class, null])
			->andReturn(true);

		$request = new AddAlbumRequest();
		$request->merge([RequestAttribute::PARENT_ID_ATTRIBUTE => null]);

		$this->assertTrue($request->authorize());
	}

	public function testRules(): void
	{
		$request = new AddAlbumRequest();

		$rules = $request->rules();

		$this->assertArrayHasKey(RequestAttribute::PARENT_ID_ATTRIBUTE, $rules);
		$this->assertArrayHasKey(RequestAttribute::TITLE_ATTRIBUTE, $rules);

		$this->assertEquals(['present', new RandomIDRule(true)], $rules[RequestAttribute::PARENT_ID_ATTRIBUTE]);
		$this->assertEquals(['required', new TitleRule()], $rules[RequestAttribute::TITLE_ATTRIBUTE]);
	}
}