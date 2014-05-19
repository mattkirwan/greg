<?php

namespace Api\Staff;

use League\Fractal\TransformerAbstract;
use Api\Staff\Model\Staff;

class StaffTransformer extends TransformerAbstract
{
	public function transform(Staff $staff)
	{
		return array(
			'id' => (int) $staff->id,
		);
	}
}