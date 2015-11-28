<?php

namespace Managers;
use Lib\Manager;

class					CommentManager extends Manager
{
	public function		__construct($dao)
	{
		parent::__construct($dao);
		$this->table = 'comments';
		$this->entity = 'Entity\\Comment';
	}

	public function		newComment(array $data)
	{
		$data['timestamp'] = date("Y-m-d H:i:s", time());
		if ($this->insert($data))
			return true;
		return false;
	}

	public function		getList($id)
	{
		$array = $this->select(array('article', $id), 0, 'ID DESC');
		return $array;
	}

	public function		updateComment(array $array)
	{
		if (!isset($array['id']))
			return false;
		$comment = $this->getComment($array['id']);
		$array['edited'] = $comment->edited() + 1;
		$comment->hydrate($array);
		if ($this->update($comment))
			return true;
		return false;
	}

	public function		getComment($id)
	{
		$comment = $this->select($id);
		if (count($comment) > 0)
			return $comment[0];
		return false;
	}

	public function		deleteComment($id)
	{
		$comment = $this->getComment($id);
		if ($this->delete($comment))
			return true;
		return false;
	}
}