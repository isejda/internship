WITH RECURSIVE TreeTraversal AS (
    -- Base case: Select all nodes from the hierarchy
    SELECT id, supervisor_id, subordinate_id, 0 AS depth
    FROM hierarchy
    WHERE supervisor_id IS NULL

    UNION ALL

    -- Recursive step: Traverse from parent nodes to their children
    SELECT child.id, child.supervisor_id, child.subordinate_id, parent.depth + 1 AS depth
    FROM hierarchy AS child
             JOIN TreeTraversal AS parent ON child.supervisor_id = parent.subordinate_id
)
UPDATE hierarchy AS h
    JOIN TreeTraversal AS t ON h.id = t.id
    SET h.depth = t.depth;


WITH RECURSIVE SupervisedUsers AS (
    SELECT u.id, u.name, u.lastname, u.email, u.birthday, u.role
    FROM users u
             JOIN hierarchy h ON u.id = h.subordinate_id
    WHERE h.supervisor_id = (SELECT id FROM users WHERE id = '56')

    UNION ALL

    SELECT u.id, u.name, u.lastname, u.email, u.birthday, u.role
    FROM users u
             JOIN hierarchy h ON u.id = h.subordinate_id
             JOIN SupervisedUsers su ON h.supervisor_id = su.id
)
SELECT * FROM SupervisedUsers;

WITH RECURSIVE SupervisedUsers AS (
    SELECT u.id, u.name, u.lastname, u.email, u.birthday, u.role
    FROM users u
             JOIN hierarchy h ON u.id = h.subordinate_id
    WHERE h.supervisor_id = (
        SELECT id FROM users WHERE id = :user_id
    )

    UNION ALL

    SELECT u.id, u.name, u.lastname, u.email, u.birthday, u.role
    FROM users u
             JOIN hierarchy h ON u.id = h.subordinate_id
             JOIN SupervisedUsers su ON h.supervisor_id = su.id
)
SELECT COUNT(*) as total FROM SupervisedUsers;