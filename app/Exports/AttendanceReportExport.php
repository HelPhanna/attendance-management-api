<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class AttendanceReportExport implements FromArray, ShouldAutoSize, WithTitle
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['Attendance Report'];
        $rows[] = ['Period', $this->data['period']['from'] . ' to ' . $this->data['period']['to']];
        $rows[] = ['Term', $this->data['term_name']];
        $rows[] = ['Class', $this->data['class_name']];
        $rows[] = [];

       
        $rows[] = [
            'Student Code',
            'Student Name',
            'Class',
            'Present',
            'Absent',
            'Permission'
        ];

        
        foreach ($this->data['rows'] as $row) {
            $rows[] = [
                $row['student_code'],
                $row['name'],
                $row['class_name'],
                $row['present'],
                $row['absent'],
                $row['permission'],
            ];
        }

        $rows[] = [];

        $rows[] = [
            'TOTAL',
            '',
            '',
            $this->data['totals']['present'],
            $this->data['totals']['absent'],
            $this->data['totals']['permission'],
        ];

        return $rows;
    }

    public function title(): string
    {
        return 'Attendance Report';
    }
}