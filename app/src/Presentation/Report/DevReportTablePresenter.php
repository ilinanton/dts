<?php

declare(strict_types=1);

namespace App\Presentation\Report;

use App\Application\Report\DevReportPresenterInterface;
use App\Application\Report\ScoredDeveloper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Output\OutputInterface;

final readonly class DevReportTablePresenter implements DevReportPresenterInterface
{
    public function __construct(
        private OutputInterface $output,
    ) {
    }

    /** @param array<ScoredDeveloper> $scoredDevelopers */
    public function render(array $scoredDevelopers): void
    {
        $rows = $this->prepareRows($scoredDevelopers);
        $this->printTable($rows);
    }

    /** @param array<ScoredDeveloper> $scoredDevelopers */
    private function prepareRows(array $scoredDevelopers): array
    {
        $rows = [];
        foreach ($scoredDevelopers as $scoredDeveloper) {
            $stat = $scoredDeveloper->statistics;
            $rows[] = [
                $stat->userId->value,
                $stat->userName->value,
                $stat->mergeRequestsCreated->value,
                $stat->approvalsGiven->value,
                $stat->mergeRequestsMerged->value,
                $stat->mergeRequestsMergedWithApproval->value,
                $stat->mergeRequestsTested->value,
                $stat->linesAdded->value,
                $stat->linesDeleted->value,
                $stat->getTotalLinesChanged(),
                $stat->mergeRequestsSelfApproved->value,
                $stat->commitsToDefaultBranch->value,
                $scoredDeveloper->score,
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
