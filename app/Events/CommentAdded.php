<?php


namespace App\Events;


use App\Models\Comment;

class CommentAdded
{
    /**
     * New added comment.
     *
     * @var Comment
     */
    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}