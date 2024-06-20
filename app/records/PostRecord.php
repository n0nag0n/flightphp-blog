<?php

declare(strict_types=1);

namespace app\records;

/**
 * ActiveRecord class for the posts table.
 * @link https://docs.flightphp.com/awesome-plugins/active-record
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $username
 * @property string $created_at
 * @property string $updated_at
 */
class PostRecord extends \flight\ActiveRecord
{
    /**
     * @var array $relations Set the relationships for the model
     *   https://docs.flightphp.com/awesome-plugins/active-record#relationships
     */
    protected array $relations = [
		'comments' => [self::HAS_MANY, CommentRecord::class, 'post_id'],
	];

    /**
     * Constructor
     * @param mixed $databaseConnection The connection to the database
     */
    public function __construct($databaseConnection)
    {
        parent::__construct($databaseConnection, 'posts');
    }

	public function beforeInsert(): void
	{
		$this->created_at = gmdate('Y-m-d H:i:s');
		$this->updated_at = null;
	}

	public function beforeUpdate(): void
	{
		$this->updated_at = gmdate('Y-m-d H:i:s');
	}
}
