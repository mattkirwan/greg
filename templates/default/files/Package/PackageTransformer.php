<?php

namespace Api\Package;

use League\Fractal\TransformerAbstract;
use Api\Package\Model\Package;

class PackageTransformer extends TransformerAbstract
{
	public function transform(Package $package)
	{
		return array(
			'id' => (int) $package->id,
		);
	}
}