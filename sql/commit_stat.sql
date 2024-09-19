SELECT COUNT(*) cnt
FROM gitlab_user u
INNER JOIN gitlab_user_x_git_user x ON x.gitlab_user_id = u.id
GROUP BY u.id
;

SELECT -- *
    x.gitlab_user_id,
    COUNT(*) AS commite_cnt,
    SUM(stats_additions) AS additions,
    SUM(stats_deletions) AS deletions,
    SUM(stats_total) AS total,
    GROUP_CONCAT(DISTINCT x.committer_email ORDER BY x.committer_email DESC SEPARATOR ', ') AS committer_emails
FROM gitlab_commit c
INNER JOIN gitlab_user_x_git_user x ON x.committer_email = c.committer_email
WHERE NOT REGEXP_LIKE(title, '^(chore|merge|revert )')
#   AND created_at >= '2024-07-01'
GROUP BY x.gitlab_user_id
ORDER BY x.gitlab_user_id
;

SELECT DISTINCT committer_email
FROM gitlab_commit;

SELECT COUNT(*), u.id, u.name
FROM gitlab_user u
INNER JOIN gitlab_user_x_git_user x ON x.gitlab_user_id = u.id
INNER JOIN gitlab_commit c ON c.committer_email = x.committer_email
INNER JOIN gitlab_commit_stats s ON s.id = c.id
GROUP BY u.id



