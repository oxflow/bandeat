<?php

namespace Entity;
use Lib\Entity;

class					Article extends Entity
{
	private				$title;
	private				$content;
	private				$author;
	private				$timestamp;
	private				$timestampUpdate;
	private				$edited;

	public function		title()
	{
		return $this->title;
	}

	public function		content()
	{
		return $this->content;
	}

	public function		author()
	{
		return $this->author;
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

	public function		setTitle($title)
	{
		if (is_string($title))
			$this->title = $title;
		return $this;
	}

	public function		setContent($content)
	{
		if (is_string($content))
			$this->content = $content;
		return $this;
	}

	public function		setAuthor($author)
	{
		$this->author = $author;
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
		$this->edited = $edited;
		return $this;
	}
}