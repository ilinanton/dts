<?php

declare(strict_types=1);

namespace App\Presentation\Report;

use App\Application\Report\DevReportPresenterInterface;
use App\Application\Report\ScoredDeveloper;
use App\Domain\Report\ReportCriteria;
use Symfony\Component\Console\Output\OutputInterface;

final readonly class DevReportHtmlPresenter implements DevReportPresenterInterface
{
    public function __construct(
        private string $outputDir,
        private OutputInterface $output,
    ) {
    }

    /** @param array<ScoredDeveloper> $scoredDevelopers */
    public function render(array $scoredDevelopers, ReportCriteria $criteria): void
    {
        $html = $this->buildHtml($scoredDevelopers, $criteria);
        $filePath = $this->saveFile($html);
        $this->output->writeln('Report saved: ' . $filePath);
    }

    private function saveFile(string $html): string
    {
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0755, true);
        }
        $path = $this->outputDir . '/report_' . date('Y-m-d_H-i-s') . '.html';
        file_put_contents($path, $html);
        return $path;
    }

    /** @param array<ScoredDeveloper> $scoredDevelopers */
    private function buildHtml(array $scoredDevelopers, ReportCriteria $criteria): string
    {
        $rows = $this->buildTableRows($scoredDevelopers);
        $generated = $this->escape(date('Y-m-d H:i:s'));
        $periodFrom = $this->escape(substr($criteria->startDate->getValue(), 0, 10));
        $periodTo = $this->escape(date('Y-m-d'));

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dev Report — {$periodFrom} / {$periodTo}</title>
<style>{$this->css()}</style>
</head>
<body>
<div class="wrap">
<h1>Developer Report</h1>
<p class="meta">Period: <strong>{$periodFrom}</strong> — <strong>{$periodTo}</strong> &nbsp;·&nbsp; Generated: {$generated}</p>
<div class="scroll">
<table id="report">
<thead>
<tr class="group">
<th colspan="2">User</th>
<th colspan="2">MR Activity</th>
<th colspan="3">Merged to default</th>
<th colspan="3">Lines of code</th>
<th colspan="2">Info</th>
<th></th>
</tr>
<tr class="cols">
<th data-col="0">id</th>
<th data-col="1">name</th>
<th data-col="2">created</th>
<th data-col="3">approvals</th>
<th data-col="4">total</th>
<th data-col="5">approved</th>
<th data-col="6">tested</th>
<th data-col="7">add</th>
<th data-col="8">del</th>
<th data-col="9">total</th>
<th data-col="10">self approved</th>
<th data-col="11">to main</th>
<th data-col="12">score</th>
</tr>
</thead>
<tbody>
{$rows}
</tbody>
</table>
</div>
</div>
<script>{$this->js()}</script>
</body>
</html>
HTML;
    }

    /** @param array<ScoredDeveloper> $scoredDevelopers */
    private function buildTableRows(array $scoredDevelopers): string
    {
        $html = '';
        foreach ($scoredDevelopers as $dev) {
            $html .= $this->buildRow($dev);
        }
        return $html;
    }

    private function buildRow(ScoredDeveloper $dev): string
    {
        $s = $dev->statistics;
        $name = $this->escape($s->userName->value);
        $score = $dev->score->formatted();

        return <<<HTML
<tr>
<td>{$s->userId->value}</td>
<td class="name">{$name}</td>
<td>{$s->mergeRequestsCreated->value}</td>
<td>{$s->approvalsGiven->value}</td>
<td>{$s->mergeRequestsMerged->value}</td>
<td>{$s->mergeRequestsMergedWithApproval->value}</td>
<td>{$s->mergeRequestsTested->value}</td>
<td>{$s->linesAdded->value}</td>
<td>{$s->linesDeleted->value}</td>
<td>{$s->getTotalLinesChanged()}</td>
<td>{$s->mergeRequestsSelfApproved->value}</td>
<td>{$s->commitsToDefaultBranch->value}</td>
<td class="score">{$score}</td>
</tr>
HTML;
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function css(): string
    {
        return <<<'CSS'
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: system-ui, sans-serif; font-size: 14px; background: #f5f7fa; color: #1a1a2e; }
.wrap { max-width: 1400px; margin: 0 auto; padding: 24px 16px; }
h1 { font-size: 22px; font-weight: 600; margin-bottom: 4px; }
.meta { color: #666; font-size: 12px; margin-bottom: 20px; }
.scroll { overflow-x: auto; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,.12); }
table { width: 100%; border-collapse: collapse; background: #fff; font-family: 'SF Mono', 'Cascadia Code', 'Fira Code', 'Consolas', monospace; }
thead { background: #1e3a5f; color: #fff; }
tr.group th { padding: 8px 12px; font-size: 11px; font-weight: 500; text-transform: uppercase;
    letter-spacing: .06em; border-right: 1px solid rgba(255,255,255,.15); text-align: center; }
tr.group th:last-child { border-right: none; }
tr.cols th { padding: 7px 12px; font-size: 12px; font-weight: 600; white-space: nowrap;
    cursor: pointer; user-select: none; border-top: 1px solid rgba(255,255,255,.1);
    border-right: 1px solid rgba(255,255,255,.1); text-align: right; }
tr.cols th:nth-child(1), tr.cols th:nth-child(2) { text-align: left; }
tr.cols th:last-child { border-right: none; }
tr.cols th:hover { background: rgba(255,255,255,.1); }
tr.cols th.asc::after { content: " \2191"; opacity: .7; }
tr.cols th.desc::after { content: " \2193"; opacity: .7; }
tbody tr { border-bottom: 1px solid #eef0f4; transition: background .15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr.top { background: #eaf7ef; }
tbody tr:hover { background: #f0f4ff; }
td { padding: 8px 12px; text-align: right; white-space: nowrap; }
td:nth-child(1), td.name { text-align: left; }
td.name { font-weight: 500; min-width: 120px; }
td.score { font-weight: 700; min-width: 80px; }
CSS;
    }

    private function js(): string
    {
        return <<<'JS'
const table = document.getElementById("report");
const headers = table.querySelectorAll("th[data-col]");
let sortCol = 12;
let sortAsc = false;

headers.forEach(th => {
    if (parseInt(th.dataset.col) === sortCol) {
        th.classList.add("desc");
    }
    th.addEventListener("click", () => {
        const col = parseInt(th.dataset.col);
        sortAsc = sortCol === col ? !sortAsc : false;
        sortCol = col;
        headers.forEach(h => h.classList.remove("asc", "desc"));
        th.classList.add(sortAsc ? "asc" : "desc");
        sortTable(col, sortAsc);
    });
});

function sortTable(col, asc) {
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.rows);
    rows.sort((a, b) => {
        const av = a.cells[col].textContent.trim();
        const bv = b.cells[col].textContent.trim();
        const an = parseFloat(av);
        const bn = parseFloat(bv);
        const cmp = isNaN(an) || isNaN(bn) ? av.localeCompare(bv) : an - bn;
        return asc ? cmp : -cmp;
    });
    rows.forEach(r => tbody.appendChild(r));
    highlightTop();
}

function highlightTop() {
    const rows = table.tBodies[0].rows;
    for (let i = 0; i < rows.length; i++) {
        rows[i].classList.toggle("top", i < 3);
    }
}

highlightTop();
JS;
    }
}
