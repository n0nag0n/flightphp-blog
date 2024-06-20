<?php

declare(strict_types=1);

namespace app\records;

/**
 * ActiveRecord class for the comments table.
 * @link https://docs.flightphp.com/awesome-plugins/active-record
 *
 * @property int $id
 * @property int $post_id
 * @property string $username
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 */
class CommentRecord extends \flight\ActiveRecord
{
    /**
     * @var array $relations Set the relationships for the model
     *   https://docs.flightphp.com/awesome-plugins/active-record#relationships
     */
    protected array $relations = [
		'post' => [self::BELONGS_TO, PostRecord::class, 'post_id'],
	];

    /**
     * Constructor
     * @param mixed $databaseConnection The connection to the database
     */
    public function __construct($databaseConnection)
    {
        parent::__construct($databaseConnection, 'comments');
    }

	public function beforeInsert(): void
	{
		$this->created_at = gmdate('Y-m-d H:i:s');
		$this->updated_at = null;
	}
}
