SELECT DISTINCT author_email
FROM gitlab_commit
;

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



SELECT u.id, u.name, COUNT(*) AS cnt, SUM(s.additions) AS additions, SUM(s.deletions) AS deletions,
       SUM(s.additions) + SUM(s.deletions) AS total,
       SUM(s.files) AS files
FROM gitlab_user u
INNER JOIN gitlab_user_x_git_user x ON x.gitlab_user_id = u.id
INNER JOIN gitlab_commit c ON c.author_email = x.git_email
INNER JOIN gitlab_commit_stats s ON s.project_id = c.project_id AND s.git_commit_id = c.git_commit_id
WHERE c.created_at >= '2024-07-01'
GROUP BY u.id
# ORDER BY c.committed_date DESC
# ORDER BY additions DESC
# ORDER BY files DESC
ORDER BY total DESC
;

SELECT * -- u.id, u.name, COUNT(*) AS cnt, SUM(s.additions) AS additions, SUM(s.deletions) AS deletions, SUM(s.files) AS files
FROM gitlab_user u
         INNER JOIN gitlab_user_x_git_user x ON x.gitlab_user_id = u.id
         INNER JOIN gitlab_commit c ON c.author_email = x.git_email
         INNER JOIN gitlab_commit_stats s ON s.project_id = c.project_id AND s.git_commit_id = c.git_commit_id
WHERE c.created_at >= '2024-07-21' AND u.id = 3722348 AND c.created_at < '2024-07-23'
#   AND c.id = 34f8320ad4baa66cece719a59d684d0bdc5ef61e
# GROUP BY u.id
ORDER BY c.committed_date DESC
# ORDER BY additions DESC
# ORDER BY files DESC
# ORDER BY cnt DESC


