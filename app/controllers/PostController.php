<?php

declare(strict_types=1);

namespace app\controllers;

use app\records\CommentRecord;
use app\records\PostRecord;

class PostController extends BaseController
{

	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void
	{
		$PostRecord = new PostRecord();
		$posts = $PostRecord->order('id DESC')->findAll();
		$CommentRecord = new CommentRecord();
		foreach($posts as &$post) {
			$post->comments = $CommentRecord->eq('post_id', $post->id)->findAll();
		}
		$this->render('posts/index.latte', [ 'page_title' => 'Blog', 'posts' => $posts]);
	}

	/**
	 * Create
	 *
	 * @return void
	 */
	public function create(): void
	{
		if($this->permission('post.create') === false) {
			$this->redirect($this->getUrl('blog'));
		}
		$this->render('posts/create.latte', [ 'page_title' => 'Create Post']);
	}

	/**
	 * Store
	 *
	 * @return void
	 */
	public function store(): void
	{
		if($this->permission('post.create') === false) {
			$this->redirect($this->getUrl('blog'));
		}
		$postData = $this->request()->data;
		$PostRecord = new PostRecord();
		$PostRecord->title = $postData->title;
		$PostRecord->content = $postData->content;
		$PostRecord->username = $this->session()->get('user');
		$PostRecord->save();
		$this->redirect($this->getUrl('blog'));
	}

	/**
	 * Show
	 *
	 * @param int $id The ID of the post
	 * @return void
	 */
	public function show(int $id): void
	{
		$PostRecord = new PostRecord();
		$post = $PostRecord->find($id);
		$this->render('posts/show.latte', [ 'page_title' => $post->title, 'post' => $post]);
	}

	/**
	 * Edit
	 *
	 * @param int $id The ID of the post
	 * @return void
	 */
	public function edit(int $id): void
	{
		if($this->permission('post.update') === false) {
			$this->redirect($this->getUrl('blog'));
		}
		$PostRecord = new PostRecord();
		$post = $PostRecord->find($id);
		$this->render('posts/edit.latte', [ 'page_title' => 'Update Post', 'post' => $post]);
	}

	/**
	 * Update
	 *
	 * @param int $id The ID of the post
	 * @return void
	 */
	public function update(int $id): void
	{
		if($this->permission('post.update') === false) {
			$this->redirect($this->getUrl('blog'));
		}
		$postData = $this->request()->data;
		$PostRecord = new PostRecord();
		$PostRecord->find($id);
		$PostRecord->title = $postData->title;
		$PostRecord->content = $postData->content;
		$PostRecord->save();
		$this->redirect($this->getUrl('blog'));
	}

	/**
	 * Destroy
	 *
	 * @param int $id The ID of the post
	 * @return void
	 */
	public function destroy(int $id): void
	{
		if($this->permission('post.delete') === false) {
			$this->redirect($this->getUrl('blog'));
		}
		$PostRecord = new PostRecord();
		$CommentRecord = new CommentRecord();
		$CommentRecord
			->eq('post_id', $id)
			->delete();
		$post = $PostRecord->find($id);
		$post->delete();
		$this->redirect($this->getUrl('blog'));
	}
}