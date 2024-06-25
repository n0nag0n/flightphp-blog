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
		$PostRecord = new PostRecord($this->db());
		$posts = $PostRecord->order('id DESC')->findAll();
		$CommentRecord = new CommentRecord($this->db());
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
		$PostRecord = new PostRecord($this->db());
		$PostRecord->title = $postData->title;
		$PostRecord->content = $postData->content;
		$PostRecord->username = $this->session()->get('user');
		$PostRecord->created_at = gmdate('Y-m-d H:i:s');
		$PostRecord->updated_at = null;
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
		$PostRecord = new PostRecord($this->db());
		$post = $PostRecord->find($id);
		$CommentRecord = new CommentRecord($this->db());
		$post->comments = $CommentRecord->eq('post_id', $post->id)->findAll();
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
		$PostRecord = new PostRecord($this->db());
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
		$PostRecord = new PostRecord($this->db());
		$PostRecord->find($id);
		$PostRecord->title = $postData->title;
		$PostRecord->content = $postData->content;
		$PostRecord->updated_at = gmdate('Y-m-d H:i:s');
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
		$PostRecord = new PostRecord($this->db());
		$CommentRecord = new CommentRecord($this->db());
		$CommentRecord->eq('post_id', $id)->delete();
		$post = $PostRecord->find($id);
		$post->delete();
		$this->redirect($this->getUrl('blog'));
	}
}