import React from 'react';

// BAD: No Props interface or type definition; any defeats TypeScript
const PostCard = (props: any) => {
    // BAD: Magic numbers 50 and 3 hardcoded, no constants
    const truncate = (text: string) => {
        if (text.length > 50) {
            return text.substring(0, 50) + '...';
        }
        return text;
    };
    // BAD: Optional chaining missing - props.body may be undefined
    const body = props.body ?? '';

    return (
        <div className="card">
            <h2>{truncate(props.title)}</h2>
            <p>Author ID: {props.userId}</p>
            <div>{body}</div>
            <button onClick={() => props.onLike()}>Like</button>
        </div>
    );
};

export default PostCard;
