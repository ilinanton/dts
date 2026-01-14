<?php

declare(strict_types=1);

namespace App\Presentation\Report;

use App\Domain\Report\DeveloperStatistics;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Output\ConsoleOutput;

final readonly class DevReportTablePresenter
{
    public function __construct(
        private ConsoleOutput $output,
    ) {
    }

    /**
     * @param array<DeveloperStatistics> $statistics
     * @param array<float> $scores
     */
    public function render(array $statistics, array $scores): void
    {
        $rows = $this->prepareRows($statistics, $scores);
        $this->printTable($rows);
    }

    private function prepareRows(array $statistics, array $scores): array
    {
        $rows = [];
        foreach ($statistics as $index => $stat) {
            $rows[] = [
                $stat->userId,
                $stat->userName,
                $stat->mergeRequestsApproved,
                $stat->mergeRequestsCreated,
                $stat->mergeRequestsMergedWithoutApproval,
                $stat->mergeRequestsSelfApproved,
                $stat->mergeRequestsMerged,
                $stat->commitsToDefaultBranch,
                $stat->linesAdded,
                $stat->linesDeleted,
                $stat->getTotalLinesChanged(),
                $scores[$index],
            ];
        }
        return $rows;
    }

    private function printTable(array $rows): void
    {
        $table = new Table($this->output);
        $table->setStyle('markdown');
        $table
            ->setHeaders([
                [
                    new TableCell('User', ['colspan' => 2]),
                    new TableCell('Merge request', ['colspan' => 2]),
                    new TableCell('Merge request merged', ['colspan' => 3]),
                    new TableCell('Commit'),
                    new TableCell('Lines of code', ['colspan' => 3]),
                ],
                [
                    'id',
                    'name',
                    'approved',
                    'created',
                    new TableCell(
                        'without approv',
                        [
                            'colspan' => 1,
                            'style' => new TableCellStyle([
                                'bg' => 'red',
                            ]),
                        ],
                    ),
                    new TableCell(
                        'self approved',
                        [
                            'colspan' => 1,
                            'style' => new TableCellStyle([
                                'bg' => 'blue',
                            ]),
                        ],
                    ),
                    'total',
                    new TableCell(
                        'to def branch',
                        [
                            'colspan' => 1,
                            'style' => new TableCellStyle([
                                'bg' => 'blue',
                            ]),
                        ],
                    ),
                    'add',
                    'del',
                    'total',
                    'score',
                ],
            ])
            ->setRows($rows);
        $table->render();
    }
}
