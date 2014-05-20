<?php

namespace Api\Package;

use Api\Package\Model\Package as Model;
use Api\ApiController;
use Api\Package\PackageTransformer;

class PackageController extends ApiController
{
	protected $model;

	public function __construct(Model $model)
	{
		$this->model = $model;
	}
{{methods}}
}