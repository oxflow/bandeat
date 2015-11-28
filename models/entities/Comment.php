<?php

namespace Entity;
use Lib\Entity;

class					Comment extends Entity
{
	private				$author;
	private				$article;
	private				$content;
	private				$timestamp;
	private				$timestampUpdate;
	private				$edited;
	private				$likes;

	public function		author()
	{
		return $this->author;
	}

	public function		article()
	{
		return $this->article;
	}

	public function		content()
	{
		return $this->content;
	}

	public function		timestamp()
	{
		return $this->timestamp;
	}

	public function		timestampUpdate()
	{
		return $this->timestampUpdate;
	}

	public function		edited()
	{
		return $this->edited;
	}

	public function		likes()
	{
		return $this->likes;
	}

	public function		setAuthor($author)
	{
		if (is_int($author))
			$this->author = $author;
		return $this;
	}

	public function		setArticle($article)
	{
		if (is_int($article))
			$this->article = $article;
		return $this;
	}

	public function		setContent($content)
	{
		if (is_string($content))
			$this->content = $content;
		return $this;
	}

	public function		setTimestamp($timestamp)
	{
		$this->timestamp = $timestamp;
		return $this;
	}

	public function		setTimestampUpdate($timestampUpdate)
	{
		$this->timestampUpdate = $timestampUpdate;
		return $this;
	}

	public function		setEdited($edited)
	{
		if (is_int($edited))
			$this->edited = $edited;
		return $this;
	}

	public function		setLikes($likes)
	{
		if (is_int($likes))
			$this->likes = $likes;
		return $this;
	}
}
