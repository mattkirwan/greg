<?php

namespace Api\Staff;

use Api\Staff\Model\Staff as Model;
use Api\ApiController;
use Api\Staff\StaffTransformer;

class StaffController extends ApiController
{
	protected $model;

	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	public function createAction()
	{
		return json_encode('Create');
	}

	public function readAction($id)
	{
		return json_encode(array(
			'data' => array(
				'id' => 'apple',
			)
		));
	}

	public function updateAction($id)
	{
		return json_encode('Update staff id: '.$id);
	}

	public function deleteAction($id)
	{
		return json_encode('Delete staff id: '.$id);
	}

	public function listAction()
	{
		return json_encode(array('data' => array()));
	}
}