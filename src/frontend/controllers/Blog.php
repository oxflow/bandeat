<?php

namespace Frontend\Controllers;
use Lib\Controller,
	Lib\Link,
	Lib\Route,
	Lib\App,
	Lib\Session;

class					Blog extends Controller
{
	private				$articleMod;
	private				$userMod;
	private				$commentMod;

	public function		__construct(App $app, $controller, $action, $vars)
	{
		parent::__construct($app, $controller, $action, $vars);
		$this->articleMod = $this->managers->getManager('Article');
		$this->userMod = $this->managers->getManager('User');
		$this->commentMod = $this->managers->getManager('Comment');
		$this->page->addVar('request', $this->request);
	}

	public function		commentAction($id, $action)
	{
		if ($action !== "edit" AND $action !== "delete")
			$this->app->response()->redirect404();
		$comment = $this->commentMod->getComment($id);
		if ($comment === false)
		{
			$this->page->addVar('error', 'Comment does not exists.');
			return ;
		}
		$admin = Session::get('admin');
		$user = $this->userMod->getUser($comment->author());
		if ($user->id() == Session::get('id') OR ($admin > 1 AND $admin >= $user->admin()))
		{
			if ($action === "delete")
			{
				$this->commentMod->deleteComment($id);
				$this->page->addVar('success', 'Comment have been deleted.');
			}
			else
			{
				if ($this->request === "POST")
				{
					$comment = array('id' => $id, 'content' => $this->app->request()->postData('content'));
					$this->commentMod->updateComment($comment);
					$this->page->addVar('success', 'Comment have been updated !');
					return ;
				}
				$this->page->addVar('comment', $comment);
				$this->page->addVar('edit_url', $this->link->getUrl($this->controller, $this->action, array('id' => $id, 'action' => 'edit')));
				$this->page->addVar('delete_url', $this->link->getUrl($this->controller, $this->action, array('id' => $id, 'action' => 'delete')));
			}
		}
		else
			$this->page->addVar('error', 'You don\'t have permission to edit this comment.');
	}

	public function		createAction()
	{
		if (Session::get('admin') < 1)
			$this->app->response()->redirect404();
		$this->page->addVar('layout_title', 'Create article');
		$this->page->addVar('action', $this->link->getUrl('Blog', 'create'));
		if ($this->request === "POST")
		{
			$request = $this->app->request();
			$content = nl2br($request->postData('content'), false);
			$title = $request->postData('title');
			$author = Session::get('id');
			$manager = $this->managers->getManager('Article');
			if ($manager->createArticle($title, $content, $author))
				$this->page->addVar('success', 'Article have been created !');
			else
				$this->page->addVar('error', 'Article have not been created.');
		}
	}

	public function		readAction($id)
	{
		$article = $this->articleMod->getArticle($id);
		if ($article !== false)
		{
			$user = $this->userMod->getUser($article->author());
			$admin = Session::get('admin');

			if ($this->request === "POST")
			{
				$action = $this->app->request()->postData('goto');
				if ($action === "edit")
				{
					$url = $this->link->getUrl($this->controller, 'update', array('id' => $article->id()));
					$this->app->response()->redirect($url);
				}
				else if ($action === "delete")
				{
					$url = $this->link->getUrl($this->controller, 'delete', array('id' => $article->id()));
					$this->app->response()->redirect($url);
				}
				else
				{
					$content = $this->app->request()->postData('comment');
					$content = nl2br($content, false);
					$data = array('content' => $content, 'author' => Session::get('id'), 'article' => $article->id());
					$this->commentMod->newComment($data);
				}
			}

			if ($user->id() == Session::get('id') OR ($admin > 1 AND $admin >= $user->admin()))
				$this->page->addVar('edit_access', true);
			else
				$this->page->addVar('edit_access', false);
			$array = array();
			$comments = $this->commentMod->getList($article->id());
			foreach ($comments as $comment)
			{
				$text = $comment->content();
				$user = $this->userMod->getUser($comment->author());
				$login = $user->login();
				$date = $comment->timestamp();
				$author = $comment->author();
				$id = $comment->id();
				$edited = $comment->edited();
				$update = $comment->timestampUpdate();
				$array[] = array('text' => $text, 'user' => $login, 'date' => $date, 'id' => $id, 'author' => $author, 'edited' => $edited, 'update' => $update);
			}
			$this->page->addVar('comments', $array);
			$this->page->addVar('action', $this->link->getUrl($this->controller, $this->action));
			$this->page->addVar('layout_title', $article->title());
			$this->page->addVar('article', $article);
			$this->page->addVar('author', $user->login());
			$this->page->addVar('link', $this->link);
			$this->page->addVar('userMod', $this->userMod);
			$this->page->addVar('id', Session::get('id'));
			$this->page->addVar('admin', Session::get('admin'));
			if ($article->edited() > 0)
			{
				$this->page->addVar('edit', $article->edited());
				$this->page->addVar('edit_time', $article->timestampUpdate());
			}
		}
		else
			$this->app->response()->redirect404(); // ARTICLE NOT FOUND
	}

	public function		deleteAction($id)
	{
		$article = $this->articleMod->getArticle($id);
		if ($article !== false)
		{
			$user = $this->userMod->getUser($article->author());
			$admin = Session::get('admin');
			if ($user->id() == Session::get('id') OR ($admin > 1 AND $admin >= $user->admin()))
			{
				if ($this->request === "POST")
				{
					$this->articleMod->deleteArticle($id);
					$this->page->addVar('success', 'Article have been deleted !');
				}
				else
					$this->page->addVar('action', $this->link->getUrl($this->controller, $this->action, array('id' => $id)));
			}
			else
				$this->page->addVar('error', 'You don\'t have permission to delete this article.');
		}
		else
			$this->page->addVar('error', 'Article does not exists.');
		
	}

	public function		updateAction($id)
	{
		$article = $this->articleMod->getArticle($id);
		$user = false;
		if ($article !== false)
			$user = $this->userMod->getUser($article->author());
		if ($user !== false)
		{
			$admin = Session::get('admin');
			if ((Session::get('id') != $user->id() AND ($admin < 2 OR $admin < $user->admin())))
			{
				$this->page->addVar('error', 'You don\'t have permission to edit this article.');
				return (0);
			}
			if ($this->request === "GET")
			{
				$this->page->addVar('action', $this->link->getUrl('Blog', 'update', array('id' => $article->id())));
				$this->page->addVar('title', $article->title());
				$this->page->addVar('content', str_replace('<br>', '', $article->content()));
				if (Session::get('admin') > 2)
					$this->page->addVar('edited', $article->edited() + 1);
				
			}
			else if ($this->request === "POST")
			{
				$request = $this->app->request();
				if ($request->postExist('title') AND $request->postExist('content'))
				{
					$title = $request->postData('title');
					$content = $request->postData('content');
					if ($request->postExist('edited') AND Session::get('admin') > 2)
						$articleManager->updateArticle($id, $title, $content, $request->postData('edited'));
					else
						$articleManager->updateArticle($id, $title, $content);
					$this->page->addVar('success', 'Article have been edited !');
				}
				else
					$this->page->addVar('error', 'HTTP error.');
			}
		}
		else
			$this->page->addVar('error', 'Article doesn\'t exists.');
	}
}