<?php

class CsmBoard
{
    public function display()
    {
        $students = [];

        $db = new SQLite3('school_board_test');
        $resource = $db->query(
        "select 
                    student.id,
                    student.name as student_name,
                    group_concat(grade.name) as grades_list,
                    count(student.id) as grades_count,
                    avg(`value`) grades_avg,
                    avg(`value`) >= 7 as final_result
                from student
                left join grade on grade.student_id = student.id
                group by student.id;"
        );

        while ($row = $resource->fetchArray()) {
            $studentData = [
                'id' => $row['student_id'],
                'name' => $row['student_name'],
                'grades_list' => $row['grades_list'],
                'grades_avg' => $row['grades_avg'],
                'final_result' => 1 === $row['final_result'] ? 'Pass' : 'Fail',
            ];

            $students[] = $studentData;
        }

        header('Content-Type: application/json');
        echo json_encode($students);
    }
}
