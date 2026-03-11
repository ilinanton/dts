<?php

declare(strict_types=1);

namespace App\Presentation\Report;

use App\Application\Report\DevReportPresenterInterface;
use App\Domain\Report\DeveloperStatistics;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Output\ConsoleOutput;

final readonly class DevReportTablePresenter implements DevReportPresenterInterface
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
                $stat->mergeRequestsCreated,
                $stat->approvalsGiven,
                $stat->mergeRequestsMerged,
                $stat->mergeRequestsMergedWithApproval,
                $stat->mergeRequestsTested,
                $stat->linesAdded,
                $stat->linesDeleted,
                $stat->getTotalLinesChanged(),
                $stat->mergeRequestsSelfApproved,
                $stat->commitsToDefaultBranch,
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
                    new TableCell('MR Activity', ['colspan' => 2]),
                    new TableCell('Merged to default', ['colspan' => 3]),
                    new TableCell('Lines of code', ['colspan' => 3]),
                    new TableCell('Info', ['colspan' => 2]),
                    '',
                ],
                [
                    'id',
                    'name',
                    'created',
                    'approvals',
                    'total',
                    'approved',
                    'tested',
                    'add',
                    'del',
                    'total',
                    new TableCell(
                        'self approved',
                        [
                            'colspan' => 1,
                            'style' => new TableCellStyle([
                                'bg' => 'blue',
                            ]),
                        ],
                    ),
                    new TableCell(
                        'to main',
                        [
                            'colspan' => 1,
                            'style' => new TableCellStyle([
                                'bg' => 'blue',
                            ]),
                        ],
                    ),
                    'score',
                ],
            ])
            ->setRows($rows);
        $table->render();
    }
}
