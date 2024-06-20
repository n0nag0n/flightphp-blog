<?php

declare(strict_types=1);

namespace app\controllers;

use flight\Engine;

class PostController
{
    /** @var Engine */
    protected Engine $app;

    /**
     * Constructor
     */
    public function __construct(Engine $app)
    {
        $this->app = $app;
    }

	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void
	{
		$PostRecord = new \app\records\PostRecord($this->app->db());
		$posts = $PostRecord->order('id DESC')->findAll();
		$this->app->render('posts/index.latte', [ 'page_title' => 'Blog', 'posts' => $posts]);
	}

	/**
	 * Create
	 *
	 * @return void
	 */
	public function create(): void
	{
		$this->app->render('posts/create.latte', [ 'page_title' => 'Create Post']);
	}

	/**
	 * Store
	 *
	 * @return void
	 */
	public function store(): void
	{
		$postData = $this->app->request()->data;
		$PostRecord = new \app\records\PostRecord($this->app->db());
		$PostRecord->title = $postData->title;
		$PostRecord->content = $postData->content;
		$PostRecord->username = $postData->username;
		$PostRecord->save();
		$this->app->redirect('/blog');
	}

	/**
	 * Show
	 *
	 * @param int $id The ID of the post
	 * @return void
	 */
	public function show(int $id): void
	{
		$PostRecord = new \app\records\PostRecord($this->app->db());
		$post = $PostRecord->find($id);
		$this->app->render('posts/show.latte', [ 'page_title' => $post->title, 'post' => $post]);
	}

	/**
	 * Edit
	 *
	 * @param int $id The ID of the post
	 * @return void
	 */
	public function edit(int $id): void
	{
		$PostRecord = new \app\records\PostRecord($this->app->db());
		$post = $PostRecord->find($id);
		$this->app->render('posts/edit.latte', [ 'page_title' => 'Update Post', 'post' => $post]);
	}

	/**
	 * Update
	 *
	 * @param int $id The ID of the post
	 * @return void
	 */
	public function update(int $id): void
	{
		$postData = $this->app->request()->data;
		$PostRecord = new \app\records\PostRecord($this->app->db());
		$post = $PostRecord->find($id);
		$post->title = $postData->title;
		$post->content = $postData->content;
		$post->username = $postData->username;
		$post->save();
		$this->app->redirect('/blog');
	}

	/**
	 * Destroy
	 *
	 * @param int $id The ID of the post
	 * @return void
	 */
	public function destroy(int $id): void
	{
		$PostRecord = new \app\records\PostRecord($this->app->db());
		$post = $PostRecord->find($id);
		$post->delete();
		$this->app->redirect('/blog');
	}
	

}
