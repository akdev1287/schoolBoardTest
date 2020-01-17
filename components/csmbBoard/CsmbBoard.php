<?php

class CsmbBoard
{
    public function display()
    {
        $students = [];

        $db = new SQLite3('school_board_test');
        $resource = $db->query(
        "select 
                    student.id as student_id,
                    student.name as student_name,
                    group_concat(grade.value) as grades_list,                    
                    avg(`value`) grades_avg,
                    max(`value`) > 8 as final_result
                from student
                left join grade on grade.student_id = student.id
                group by student.id;"
        );

        $xml = new SimpleXMLElement('<students/>');

        while ($row = $resource->fetchArray()) {
            $studentElement = $xml->addChild('student');
            $studentElement->addChild('id', $row['student_id']);
            $studentElement->addChild('name', $row['student_name']);
            $studentElement->addChild('grades_list', $row['grades_list']);
            $studentElement->addChild('grades_avg', $row['grades_avg']);
            $studentElement->addChild('final_result', 1 === $row['final_result'] ? 'Pass' : 'Fail');
        }

        header('Content-Type: application/xml');
        echo $xml->asXML();
    }
}
