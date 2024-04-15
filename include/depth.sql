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


SELECT
    id,
    data_hyrje,
    ora_hyrje,
    ora_dalje,
    TIMESTAMPDIFF(HOUR, ora_hyrje, ora_dalje) AS difference
FROM hyrje_dalje_kryesore;

# differenca hyrje dalje per cdo rresht
WITH difference_in_seconds AS (
    SELECT
        id,
        data_hyrje,
        ora_hyrje,
        CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
        CASE
            WHEN ora_dalje = '00:00:00' THEN
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                )
            ELSE
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                )
            END AS seconds
    FROM hyrje_dalje_kryesore
),

     differences AS (
         SELECT
             id,
             data_hyrje,
             ora_hyrje,
             ora_dalje_adjusted AS ora_dalje,
             seconds,
             MOD(seconds, 60) AS seconds_part,
             MOD(seconds, 3600) AS minutes_part,
             MOD(seconds, 3600 * 24) AS hours_part
         FROM difference_in_seconds
     )

SELECT
    id,
    data_hyrje,
    ora_hyrje,
    ora_dalje,
    CONCAT(
            FLOOR(hours_part / 3600), ' hours ',
            FLOOR(minutes_part / 60), ' minutes ',
            seconds_part, ' seconds'
    ) AS difference
FROM differences;


# oret per cdo dite per cdo user
WITH difference_in_seconds AS (
    SELECT
        id,
        username,
        data_hyrje,
        ora_hyrje,
        CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
        CASE
            WHEN ora_dalje = '00:00:00' THEN
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                )
            ELSE
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                )
            END AS seconds
    FROM hyrje_dalje_kryesore
),

     summed_differences AS (
         SELECT
             user(),
             data_hyrje,
             SUM(seconds) AS total_seconds
         FROM difference_in_seconds
         GROUP BY username, data_hyrje
     )

SELECT
    data_hyrje,
    CONCAT(
            FLOOR(total_seconds / 3600), ' hours ',
            FLOOR((total_seconds % 3600) / 60), ' minutes ',
            total_seconds % 60, ' seconds'
    ) AS total_difference
FROM summed_differences;


# oret cdo dite nga secili username
WITH difference_in_seconds AS (
    SELECT
        id,
        username,
        data_hyrje,
        ora_hyrje,
        CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
        CASE
            WHEN ora_dalje = '00:00:00' THEN
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                )
            ELSE
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                )
            END AS seconds
    FROM hyrje_dalje_kryesore
),

     summed_differences AS (
         SELECT
             username,
             data_hyrje,
             SUM(seconds) AS total_seconds
         FROM difference_in_seconds
         GROUP BY username, data_hyrje
     )

SELECT
    data_hyrje,
    CONCAT(
            FLOOR(total_seconds / 3600), ' hours ',
            FLOOR((total_seconds % 3600) / 60), ' minutes ',
            total_seconds % 60, ' seconds'
    ) AS total_difference
FROM summed_differences
WHERE username = 'oc64246';


# oret per cdo vit
WITH difference_in_seconds AS (
    SELECT
        id,
        username,
        data_hyrje,
        ora_hyrje,
        CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
        CASE
            WHEN ora_dalje = '00:00:00' THEN
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                )
            ELSE
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                )
            END AS seconds
    FROM hyrje_dalje_kryesore
),

     summed_differences AS (
         SELECT
             username,
             YEAR(data_hyrje) AS year,
             SUM(seconds) AS total_seconds
         FROM difference_in_seconds
         GROUP BY username, YEAR(data_hyrje)
     )

SELECT
    year,
    FLOOR(SUM(total_seconds) / 3600) as hours,
    FLOOR((SUM(total_seconds) % 3600) / 60) as minutes,
    SUM(total_seconds) % 60 as seconds
#     CONCAT(
#             FLOOR(SUM(total_seconds) / 3600), ' hours ',
#             FLOOR((SUM(total_seconds) % 3600) / 60), ' minutes ',
#             SUM(total_seconds) % 60, ' seconds'
#     ) AS total_difference
FROM summed_differences
WHERE username = 'oc64246'
GROUP BY year;


# cdo muaj te vitit
WITH difference_in_seconds AS (
    SELECT
        id,
        username,
        data_hyrje,
        ora_hyrje,
        CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
        CASE
            WHEN ora_dalje = '00:00:00' THEN
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                )
            ELSE
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                )
            END AS seconds
    FROM hyrje_dalje_kryesore
),

     summed_differences AS (
         SELECT
             username,
             YEAR(data_hyrje) AS year,
             MONTH(data_hyrje) AS month,
             SUM(seconds) AS total_seconds
         FROM difference_in_seconds
         WHERE username = 'oc64246'
         GROUP BY username, YEAR(data_hyrje), MONTH(data_hyrje)
     )

SELECT
    year,
    month,
    FLOOR(SUM(total_seconds) / 3600) as hours,
    FLOOR((SUM(total_seconds) % 3600) / 60) as minutes,
    SUM(total_seconds) % 60 as seconds
FROM summed_differences
GROUP BY year, month;
SELECT
    id,
    data_hyrje,
    ora_hyrje,
    ora_dalje,
    TIMESTAMPDIFF(HOUR, ora_hyrje, ora_dalje) AS difference
FROM hyrje_dalje_kryesore;

# differenca hyrje dalje per cdo rresht
WITH difference_in_seconds AS (
    SELECT
        id,
        data_hyrje,
        ora_hyrje,
        CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
        CASE
            WHEN ora_dalje = '00:00:00' THEN
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                )
            ELSE
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                )
            END AS seconds
    FROM hyrje_dalje_kryesore
),

     differences AS (
         SELECT
             id,
             data_hyrje,
             ora_hyrje,
             ora_dalje_adjusted AS ora_dalje,
             seconds,
             MOD(seconds, 60) AS seconds_part,
             MOD(seconds, 3600) AS minutes_part,
             MOD(seconds, 3600 * 24) AS hours_part
         FROM difference_in_seconds
     )

SELECT
    id,
    data_hyrje,
    ora_hyrje,
    ora_dalje,
    CONCAT(
            FLOOR(hours_part / 3600), ' hours ',
            FLOOR(minutes_part / 60), ' minutes ',
            seconds_part, ' seconds'
    ) AS difference
FROM differences;


# oret per cdo dite per cdo user
WITH difference_in_seconds AS (
    SELECT
        id,
        username,
        data_hyrje,
        ora_hyrje,
        CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
        CASE
            WHEN ora_dalje = '00:00:00' THEN
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                )
            ELSE
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                )
            END AS seconds
    FROM hyrje_dalje_kryesore
),

     summed_differences AS (
         SELECT
             user(),
             data_hyrje,
             SUM(seconds) AS total_seconds
         FROM difference_in_seconds
         GROUP BY username, data_hyrje
     )

SELECT
    data_hyrje,
    CONCAT(
            FLOOR(total_seconds / 3600), ' hours ',
            FLOOR((total_seconds % 3600) / 60), ' minutes ',
            total_seconds % 60, ' seconds'
    ) AS total_difference
FROM summed_differences;


# oret cdo dite nga secili username
WITH difference_in_seconds AS (
    SELECT
        id,
        username,
        data_hyrje,
        ora_hyrje,
        CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
        CASE
            WHEN ora_dalje = '00:00:00' THEN
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                )
            ELSE
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                )
            END AS seconds
    FROM hyrje_dalje_kryesore
),

     summed_differences AS (
         SELECT
             username,
             data_hyrje,
             SUM(seconds) AS total_seconds
         FROM difference_in_seconds
         GROUP BY username, data_hyrje
     )

SELECT
    data_hyrje,
    CONCAT(
            FLOOR(total_seconds / 3600), ' hours ',
            FLOOR((total_seconds % 3600) / 60), ' minutes ',
            total_seconds % 60, ' seconds'
    ) AS total_difference
FROM summed_differences
WHERE username = 'oc64246';


# oret per cdo vit
WITH difference_in_seconds AS (
    SELECT
        id,
        username,
        data_hyrje,
        ora_hyrje,
        CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
        CASE
            WHEN ora_dalje = '00:00:00' THEN
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                )
            ELSE
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                )
            END AS seconds
    FROM hyrje_dalje_kryesore
),

     summed_differences AS (
         SELECT
             username,
             YEAR(data_hyrje) AS year,
             SUM(seconds) AS total_seconds
         FROM difference_in_seconds
         GROUP BY username, YEAR(data_hyrje)
     )

SELECT
    year,
    FLOOR(SUM(total_seconds) / 3600) as hours,
    FLOOR((SUM(total_seconds) % 3600) / 60) as minutes,
    SUM(total_seconds) % 60 as seconds
#     CONCAT(
#             FLOOR(SUM(total_seconds) / 3600), ' hours ',
#             FLOOR((SUM(total_seconds) % 3600) / 60), ' minutes ',
#             SUM(total_seconds) % 60, ' seconds'
#     ) AS total_difference
FROM summed_differences
WHERE username = 'oc64246'
GROUP BY year;


# cdo muaj te vitit
WITH difference_in_seconds AS (
    SELECT
        id,
        username,
        data_hyrje,
        ora_hyrje,
        CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
        CASE
            WHEN ora_dalje = '00:00:00' THEN
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                )
            ELSE
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                )
            END AS seconds
    FROM hyrje_dalje_kryesore
),

     summed_differences AS (
         SELECT
             username,
             YEAR(data_hyrje) AS year,
             MONTH(data_hyrje) AS month,
             SUM(seconds) AS total_seconds
         FROM difference_in_seconds
         WHERE username = 'oc64246'
         GROUP BY username, YEAR(data_hyrje), MONTH(data_hyrje)
     )

SELECT
    year,
    month,
    FLOOR(SUM(total_seconds) / 3600) as hours,
    FLOOR((SUM(total_seconds) % 3600) / 60) as minutes,
    SUM(total_seconds) % 60 as seconds
FROM summed_differences
GROUP BY year, month;
