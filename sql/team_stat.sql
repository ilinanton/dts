SELECT id, name, mr_created, mr_merged, mr_merged_without_approv, mr_approved, mr_self_approved,
       committed_to_default_branch, -- loc_add, loc_del, (loc_add + loc_del) AS total_loc,
       (
           mr_created * mr_created_point +
           mr_merged * mr_merged_point +
           mr_merged_without_approv * mr_merged_without_approv_point +
           mr_approved * mr_approved_point +
           mr_self_approved * mr_self_approved_point +
           committed_to_default_branch * committed_to_default_branch_point
#           + loc_add * loc_add_point +
#            loc_del * loc_del_point
       ) AS score,
       after_at

FROM (
         SELECT u.id, u.name,
                d.date AS after_at,
                (
                    SELECT COUNT(*)
                    FROM gitlab_merge_request mr
                    INNER JOIN gitlab_project p ON p.id = mr.project_id
                    WHERE mr.author_id = u.id
                      AND mr.created_at > d.date
                      AND mr.source_branch <> p.default_branch
                ) AS mr_created, 0.5 AS mr_created_point,
                (
                    SELECT COUNT(*)
                    FROM gitlab_merge_request mr
                    INNER JOIN gitlab_project p ON p.id = mr.project_id
                    WHERE mr.author_id = u.id
                      AND mr.state = 'merged'
                      AND mr.merged_at > d.date
                      AND mr.source_branch <> p.default_branch
                ) AS mr_merged, 1 AS mr_merged_point,
                (
                    SELECT COUNT(*)
                    FROM gitlab_merge_request mr
                    INNER JOIN gitlab_project p ON p.id = mr.project_id
                    WHERE mr.author_id = u.id
                      AND mr.state = 'merged'
                      AND mr.merged_at > d.date
                      AND mr.target_branch = p.default_branch
                      AND NOT EXISTS(
                        SELECT 'x'
                        FROM gitlab_event e
                        WHERE e.target_id = mr.id
                          AND e.action_name = 'approved'
                    )
                ) AS mr_merged_without_approv, -0.5 AS mr_merged_without_approv_point,
                (
                    SELECT COUNT(*)
                    FROM gitlab_event e
                    WHERE e.author_id = u.id
                      AND e.created_at > d.date
                      AND e.action_name = 'approved'
                ) AS mr_approved, 1 AS mr_approved_point,
                (
                    SELECT COUNT(*)
                    FROM gitlab_event e
                    INNER JOIN gitlab_merge_request mr
                            ON mr.author_id = e.author_id
                           AND mr.id = e.target_id
                    WHERE e.author_id = u.id
                      AND e.created_at > d.date
                      AND e.action_name = 'approved'
                ) AS mr_self_approved, 0 AS mr_self_approved_point,
                (
                    SELECT COUNT(*)
                    FROM gitlab_event e
                    INNER JOIN gitlab_project p
                            ON p.id = e.project_id
                           AND p.default_branch = e.push_data_ref
                    WHERE e.author_id = u.id
                      AND e.created_at > d.date
                      AND e.action_name = 'pushed to'
                      AND e.push_data_ref_type = 'branch'
                      AND e.push_data_commit_title NOT LIKE 'Merge branch%'
                ) AS committed_to_default_branch, 0 AS committed_to_default_branch_point
#                 ,
#                 IFNULL((
#                     SELECT SUM(stats_additions)
#                     FROM gitlab_event e
#                     INNER JOIN gitlab_commit c ON c.id = e.push_data_commit_to
#                     INNER JOIN gitlab_project p ON p.id = e.project_id
#                     WHERE e.author_id = u.id
#                       AND e.push_data_ref = p.default_branch
#                       AND e.push_data_action = 'pushed'
#                       AND e.created_at > d.date
#                 ), 0) AS loc_add, 0.01 AS loc_add_point,
#                 IFNULL((
#                     SELECT SUM(stats_deletions)
#                     FROM gitlab_event e
#                     INNER JOIN gitlab_commit c ON c.id = e.push_data_commit_to
#                     INNER JOIN gitlab_project p ON p.id = e.project_id
#                     WHERE e.author_id = u.id
#                       AND e.push_data_ref = p.default_branch
#                       AND e.push_data_action = 'pushed'
#                       AND e.created_at > d.date
#                 ), 0) AS loc_del, 0.01 AS loc_del_point

         FROM gitlab_user u
         LEFT JOIN (
#            SELECT DATE_FORMAT(NOW() - INTERVAL 6 DAY, '%Y.%m.%d') AS date
#            SELECT DATE_FORMAT(LAST_DAY(NOW()) - INTERVAL 10 MONTH , '%Y.%m.%d') + INTERVAL 1 DAY AS date
             SELECT '2024.07.01 00:00:00' AS date
             FROM DUAL
         ) d ON 1 = 1
         GROUP BY u.id
     ) dat
ORDER BY score DESC
;

SELECT COUNT(*) AS cnt, e.author_id, u.name
FROM gitlab_event e
INNER JOIN gitlab_user u ON u.id = e.author_id
INNER JOIN gitlab_project p ON p.id = e.project_id AND p.default_branch = e.push_data_ref
WHERE 1 = 1
  AND e.action_name = 'pushed to'
  AND e.push_data_ref_type = 'branch'
  AND e.push_data_commit_title NOT LIKE 'Merge branch%'
GROUP BY e.author_id
ORDER BY cnt DESC
;
