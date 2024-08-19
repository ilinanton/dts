SELECT d_day,
    (
        SELECT COUNT(*)
        FROM git_lab_merge_request mr
        INNER JOIN git_lab_project p ON p.id = mr.project_id
        WHERE mr.author_id = u.id
          AND DATE_FORMAT(mr.created_at, '%Y-%m-%d') = d_day
          AND mr.source_branch <> p.default_branch
    ) AS mr_created,
   (
       SELECT COUNT(*)
       FROM git_lab_merge_request mr
       INNER JOIN git_lab_project p ON p.id = mr.project_id
       WHERE mr.author_id = u.id
         AND mr.state = 'merged'
         AND DATE_FORMAT(mr.merged_at, '%Y-%m-%d') = d_day
         AND mr.source_branch <> p.default_branch
   ) AS mr_merged,
   (
       SELECT COUNT(*)
       FROM git_lab_merge_request mr
       INNER JOIN git_lab_project p ON p.id = mr.project_id
       WHERE mr.author_id = u.id
         AND mr.state = 'merged'
         AND DATE_FORMAT(mr.merged_at, '%Y-%m-%d') = d_day
         AND mr.target_branch = p.default_branch
         AND NOT EXISTS(
           SELECT 'x'
           FROM git_lab_event e
           WHERE e.target_id = mr.id
             AND e.action_name = 'approved'
       )
   ) AS mr_merged_without_approv,
   (
       SELECT COUNT(*)
       FROM git_lab_event e
       WHERE e.author_id = u.id
         AND DATE_FORMAT(e.created_at, '%Y-%m-%d') = d_day
         AND e.action_name = 'approved'
   ) AS mr_approved,
   (
       SELECT COUNT(*)
       FROM git_lab_event e
       INNER JOIN git_lab_merge_request mr
               ON mr.author_id = e.author_id
              AND mr.id = e.target_id
       WHERE e.author_id = u.id
         AND DATE_FORMAT(e.created_at, '%Y-%m-%d') = d_day
         AND e.action_name = 'approved'
   ) AS mr_self_approved,
   (
       SELECT COUNT(*)
       FROM git_lab_event e
       INNER JOIN git_lab_project p
               ON p.id = e.project_id
              AND p.default_branch = e.push_data_ref
       WHERE e.author_id = u.id
         AND DATE_FORMAT(e.created_at, '%Y-%m-%d') = d_day
         AND e.action_name = 'pushed to'
         AND e.push_data_ref_type = 'branch'
         AND e.push_data_commit_title NOT LIKE 'Merge branch%'
   ) AS committed_to_default_branch
#        ,
#    IFNULL((
#        SELECT SUM(stats_total)
#        FROM git_lab_event e
#        INNER JOIN git_lab_commit c ON c.id = e.push_data_commit_to
#        INNER JOIN git_lab_project p ON p.id = e.project_id
#        WHERE e.author_id = u.id
#          AND e.push_data_ref = p.default_branch
#          AND e.push_data_action = 'pushed'
#          AND DATE_FORMAT(e.created_at, '%Y-%m-%d') = d_day
#    ), 0) AS loc_total
FROM (
    SELECT d_day
    FROM (
        SELECT DATE_ADD(@date_start, INTERVAL (@day := @day + 1) DAY) AS d_day
        FROM (
            SELECT @date_start := '2024-01-01', @day := -1
            FROM dual
        ) AS i,
        (
            SELECT *
            FROM git_lab_event LIMIT 1000
        ) as tmp
    ) dates
    WHERE d_day BETWEEN '2024-07-01' AND '2024-09-30'
) dates
INNER JOIN git_lab_user u ON 1=1 AND u.id = :GIT_LAB_USER_ID
