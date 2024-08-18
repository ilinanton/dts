SELECT SUM(stats_additions) AS add_loc, SUM(stats_deletions) AS del_loc, SUM(stats_total) AS total_loc
FROM git_lab_event e
INNER JOIN git_lab_commit c ON c.id = e.push_data_commit_to
WHERE e.author_id = :AUTHOR_ID
  AND push_data_ref = 'release'
  AND push_data_action = 'pushed'
ORDER BY e.created_at DESC;
