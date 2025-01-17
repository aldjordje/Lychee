<?php

namespace App\DTO\Rights;

use App\DTO\ArrayableDTO;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;

/**
 * Data Transfer Object (DTO) to transmit the rights of an user on different user accounts.
 */
class UserManagementRightsDTO extends ArrayableDTO
{
	public function __construct(
		public bool $can_create,
		public bool $can_list,
		public bool $can_edit,
		public bool $can_delete)
	{
	}

	/**
	 * Create from current user.
	 *
	 * @return self
	 */
	public static function ofCurrentUser(): self
	{
		return new self(
			can_create: Gate::check(UserPolicy::CAN_CREATE_OR_EDIT_OR_DELETE, [User::class]),
			can_list: Gate::check(UserPolicy::CAN_LIST, [User::class]),
			can_edit: Gate::check(UserPolicy::CAN_CREATE_OR_EDIT_OR_DELETE, [User::class]),
			can_delete: Gate::check(UserPolicy::CAN_CREATE_OR_EDIT_OR_DELETE, [User::class])
		);
	}
}
