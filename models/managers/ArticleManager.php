<?php

namespace Managers;
use Lib\Manager;

class					ArticleManager extends Manager
{
	public function		__construct($dao)
	{
		parent::__construct($dao);
		$this->table = 'articles';
		$this->entity = 'Entity\\Article';
	}

	public function		getList()
	{
		return $this->select(0, 0, 'ID DESC');
	}

	public function		deleteArticle($id)
	{
		$article = $this->getArticle($id);
		if ($this->delete($article))
			return true;
		return false;
	}

	public function		updateArticle($id, $title, $content, $edited = NULL)
	{
		$article = $this->getArticle($id);
		if ($article === false)
			return false;
		$data = array(
			'id' => $id,
			'title' => $title,
			'content' => nl2br($content, false)
		);
		if ($edited !== NULL)
			$data['edited'] = $edited;
		else
			$data['edited'] = $article->edited() + 1;
		if ($this->updateArray($data))
			return true;
		return false;
	}

	public function		createArticle($title, $content, $author)
	{
		$timestamp = date('Y-m-d H:i:s', time());
		$values = array(
			'author' => $author,
			'title' => $title,
			'content' => $content,
			'timestamp' => $timestamp
		);
		if (empty($title) OR empty($content) OR !is_numeric($author))
			return false;
		if ($this->insert($values))
			return true;
		return false;
	}

	public function		getArticle($id)
	{
		$article = $this->select($id);
		if (count($article) > 0)
			return $article[0];
		return false;
	}
}