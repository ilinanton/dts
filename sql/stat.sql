SELECT id, name, mr_created, mr_merged, mr_merged_without_approv, mr_approved, mr_self_approved,
       committed_to_default_branch,
       (
           mr_created * mr_created_point +
           mr_merged * mr_merged_point +
           mr_merged_without_approv * mr_merged_without_approv_point +
           mr_approved * mr_approved_point +
           mr_self_approved * mr_self_approved_point +
           committed_to_default_branch * committed_to_default_branch_point
           ) AS score, after_at

FROM (
         SELECT u.id, u.name,
                d.date AS after_at,
                (
                    SELECT COUNT(*)
                    FROM git_lab_merge_request mr
                             INNER JOIN git_lab_project p ON p.id = mr.project_id
                    WHERE mr.author_id = u.id
                      AND mr.created_at > d.date
                      AND mr.source_branch <> p.default_branch
                ) AS mr_created, 0.5 AS mr_created_point,
                (
                    SELECT COUNT(*)
                    FROM git_lab_merge_request mr
                             INNER JOIN git_lab_project p ON p.id = mr.project_id
                    WHERE mr.author_id = u.id
                      AND mr.state = 'merged'
                      AND mr.merged_at > d.date
                      AND mr.source_branch <> p.default_branch
                ) AS mr_merged, 1 AS mr_merged_point,
                (
                    SELECT COUNT(*)
                    FROM git_lab_merge_request mr
                             INNER JOIN git_lab_project p ON p.id = mr.project_id
                    WHERE mr.author_id = u.id
                      AND mr.state = 'merged'
                      AND mr.merged_at > d.date
                      AND mr.target_branch = p.default_branch
                      AND NOT EXISTS(
                        SELECT 'x'
                        FROM git_lab_event e
                        WHERE e.target_id = mr.id
                          AND e.action_name = 'approved'
                    )
                ) AS mr_merged_without_approv, -0.5 AS mr_merged_without_approv_point,

                (
                    SELECT COUNT(*)
                    FROM git_lab_event e
                    WHERE e.author_id = u.id
                      AND e.created_at > d.date
                      AND e.action_name = 'approved'
                ) AS mr_approved, 1 AS mr_approved_point,
                (
                    SELECT COUNT(*)
                    FROM git_lab_event e
                    INNER JOIN git_lab_merge_request mr
                            ON mr.author_id = e.author_id
                           AND mr.id = e.target_id
                    WHERE e.author_id = u.id
                      AND e.created_at > d.date
                      AND e.action_name = 'approved'
                ) AS mr_self_approved, 0 AS mr_self_approved_point,
                (
                    SELECT COUNT(*)
                    FROM git_lab_event e
                    INNER JOIN git_lab_project p ON p.id = e.project_id AND p.default_branch = e.push_data_ref
                    WHERE e.author_id = u.id
                      AND e.created_at > d.date
                      AND e.action_name = 'pushed to'
                      AND e.push_data_ref_type = 'branch'
                      AND e.push_data_commit_title NOT LIKE 'Merge branch%'
                ) AS committed_to_default_branch, 0 AS committed_to_default_branch_point

         FROM git_lab_user u
                  LEFT JOIN (
             #            SELECT DATE_FORMAT(NOW() - INTERVAL 6 DAY, '%Y.%m.%d') AS date
#            SELECT DATE_FORMAT(LAST_DAY(NOW()) - INTERVAL 10 MONTH , '%Y.%m.%d') + INTERVAL 1 DAY AS date
           SELECT '2024.07.01 00:00:00' AS date
         FROM DUAL
             ) d ON 1 = 1
         GROUP BY u.id
     ) dat
    # ORDER BY mr_created DESC
ORDER BY score DESC
    # ORDER BY mr_approved DESC
    # ORDER BY mr_merged_without_approv DESC
;

SELECT COUNT(*) AS cnt, e.author_id, u.name
FROM git_lab_event e
INNER JOIN git_lab_user u ON u.id = e.author_id
INNER JOIN git_lab_project p ON p.id = e.project_id AND p.default_branch = e.push_data_ref
WHERE 1 = 1
  AND e.action_name = 'pushed to'
  AND e.push_data_ref_type = 'branch'
  AND e.push_data_commit_title NOT LIKE 'Merge branch%'
GROUP BY e.author_id
ORDER BY cnt DESC
;
