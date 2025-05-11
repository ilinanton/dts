SELECT u.name, COUNT(*) AS cnt
FROM (
    SELECT MAX(le.id) AS max_id
    FROM gitlab_resource_label_event le
    INNER JOIN gitlab_label l ON l.id = le.label_id AND l.name = 'Test Ok'
    WHERE resource_type = 'MergeRequest'
    GROUP BY resource_id
) dat
INNER JOIN gitlab_resource_label_event rle ON rle.id = dat.max_id
INNER JOIN gitlab_user u ON u.id = rle.user_id
WHERE rle.action_name = 'add'
GROUP BY u.id

