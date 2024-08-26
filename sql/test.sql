SELECT SUM(stats_additions) AS add_loc, SUM(stats_deletions) AS del_loc, SUM(stats_total) AS total_loc
FROM gitlab_event e
INNER JOIN gitlab_commit c ON c.id = e.push_data_commit_to
WHERE e.author_id = :AUTHOR_ID
  AND push_data_ref = 'release'
  AND push_data_action = 'pushed'
ORDER BY e.created_at DESC;

SELECT COUNT(*)
FROM gitlab_commit
-- WHERE committer_name IN ('Anton Ilin')
-- WHERE committer_email IN ('ilin.antonio@gmail.com', '736770-ilin.antonio@users.noreply.gitlab.com')
WHERE 1 = 1
--   AND id = '00515b4ed09d0fd6faac8fadd8052dccd1874ee7'
--    AND NOT REGEXP_LIKE(title, '^(breaking|feat|revert|refactor|perf|fix|test|style|docs)\\((DEV|IDEA|BUG|AT)-[0-9]+')
  AND NOT REGEXP_LIKE(title, '^(chore|merge|revert )')
--  AND NOT REGEXP_LIKE(title, '^(fix:|DEV-|BUG-|ci:|feat:|fix\\()')
