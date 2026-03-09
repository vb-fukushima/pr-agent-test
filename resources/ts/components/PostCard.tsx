import React, { FC } from 'react';

// Constant for character limit instead of magic number
const TITLE_MAX_LENGTH = 50;

/**
 * Prop types for PostCard component.
 */
interface PostCardProps {
    title: string;
    body: string;
    author: string;
    onLike: (id: number) => void;
    id: number;
}

/**
 * Display a post in a card format.
 * 
 * @param {PostCardProps} props
 * @returns {JSX.Element}
 */
const PostCard: FC<PostCardProps> = ({ title, body, author, onLike, id }) => {
    // Correctly typed prop and constant usage
    const truncatedTitle = title.length > TITLE_MAX_LENGTH ? `${title.substring(0, TITLE_MAX_LENGTH)}...` : title;

    return (
        <div className="card m-4 p-4 border rounded shadow">
            <h2 className="text-xl font-bold">{truncatedTitle}</h2>
            <p className="text-gray-600">by {author}</p>
            <div className="mt-4">{body}</div>
            <button
                className="btn-primary mt-4"
                onClick={() => onLike(id)}
                aria-label={`Like post ${id}`}
            >
                Like
            </button>
        </div>
    );
};

export default PostCard;
