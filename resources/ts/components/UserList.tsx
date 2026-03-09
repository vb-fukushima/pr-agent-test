import React, { useState, useEffect } from 'react';

/**
 * Display a list of users.
 */
const UserList = () => {
    // BAD: Using 'any' type instead of a proper interface
    const [users, setUsers] = useState<any[]>([]);
    const [isLoading, setIsLoading] = useState<boolean>(true);

    useEffect(() => {
        // BAD: useEffect dependency array is missing. It will run on every render if not careful.
        // BAD: No cleanup function (AbortController) to handle component unmounting.
        const fetchUsers = async () => {
            try {
                const response = await fetch('/api/users');
                // BAD: API response is not typed or validated
                const data = await response.json();
                setUsers(data);
                setIsLoading(false);
            } catch (err) {
                console.error(err);
            }
        };

        fetchUsers();
    }, []); // Empty array is okay here, but let's say we had a prop we forgot to include

    if (isLoading) return <div>Loading...</div>;

    return (
        <ul>
            {users.map((user: any) => (
                <li key={user.id}>
                    {user.name}
                </li>
            ))}
        </ul>
    );
};

export default UserList;
