<?php

class SharedResource
{
	use Model;

	protected $table = 'resources';
	protected $order_column = 'created_at';
	protected $id_column = 'resource_id';

	protected $allowedColumns = [
		'user_id',
		'title',
		'description',
		'category',
		'file_name',
		'file_path',
		'file_size',
		'file_type',
		'downloads',
		'likes',
		'status',
		'created_at',
		'updated_at',
	];
}
