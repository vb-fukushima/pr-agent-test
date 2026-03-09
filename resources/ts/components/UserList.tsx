import React, { FC, useState, useEffect } from 'react';

/**
 * Interface for User data.
 */
interface User {
    id: number;
    name: string;
    email: string;
}

/**
 * Display a list of users.
 * 
 * @returns JSX.Element
 */
const UserList: FC = () => {
    const [users, setUsers] = useState<User[]>([]);
    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        let isMounted = true;
        const controller = new AbortController();

        const fetchUsers = async () => {
            try {
                const response = await fetch('/api/users', { signal: controller.signal });
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data: User[] = await response.json();
                if (isMounted) {
                    setUsers(data);
                    setIsLoading(false);
                }
            } catch (err) {
                if (isMounted && err instanceof Error && err.name !== 'AbortError') {
                    setError(err.message);
                    setIsLoading(false);
                }
            }
        };

        fetchUsers();

        // Cleanup function for cancelling fetch and avoiding memory leaks
        return () => {
            isMounted = false;
            controller.abort();
        };
    }, []);

    if (isLoading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <ul className="user-list">
            {users.map((user) => (
                <li key={user.id} className="p-2 border-b">
                    {user.name} ({user.email})
                </li>
            ))}
        </ul>
    );
};

export default UserList;
