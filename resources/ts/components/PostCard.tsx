import React from 'react';

// BAD: No Props interface or type definition
const PostCard = (props: any) => {
    // BAD: Using magic number (50) directly in the logic
    const truncate = (text: string) => {
        if (text.length > 50) {
            return text.substring(0, 50) + '...';
        }
        return textt;
    };

    return (
        <div className="card">
            <h2>{truncate(props.title)}</h2>
            <p>Author ID: {props.userId}</p>
            <div>{props.body}</div>
            <button onClick={() => props.onLike()}>Like</button>
        </div>
    );
};

export default PostCard;
