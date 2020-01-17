<?php

class Student
{
    private $addFormErrors = [];
    private $addGradeFormErrors = [];

    public function displayAddForm(): void
    {
        $student = $this;

        ob_start();
        require 'addForm.html';
        ob_end_flush();
    }

    public function displayList(): void
    {
        $student = $this;

        ob_start();
        require 'list.html';
        ob_end_flush();
    }

    public function displayAddGradeForm(int $studentId): void
    {
        $student = $this;

        ob_start();
        require 'addGradeForm.html';
        ob_end_flush();
    }

    public function displayDetails(int $studentId): void
    {
        $student = $this;

        ob_start();
        require 'details.html';
        ob_end_flush();
    }

    public function add(string $name, string $board): bool
    {
        $success = false;

        if (trim($name) === '') {
            $this->addAddFormError('Name should not be empty');
        } elseif (!in_array($board, ['csm', 'csmb'])) {
            $this->addAddFormError('Wrong board');
        } else {
            $db = new SQLite3('school_board_test');
            $statement = $db->prepare("INSERT INTO student(name, board) VALUES(?, ?)");
            $statement->bindValue(1, $name, SQLITE3_TEXT);
            $statement->bindValue(2, $board, SQLITE3_TEXT);
            $executeResult = $statement->execute();

            $success = $executeResult instanceof SQLite3Result;
        }

        return $success;
    }

    public function addGrade(int $studentId, string $name, int $value): bool
    {
        $success = false;

        if (trim($name) === '') {
            $this->addAddGradeFormError('Name should not be empty');
        } else {
            $db = new SQLite3('school_board_test');
            $statement = $db->prepare("INSERT INTO grade(student_id, name, value) VALUES(?, ?, ?)");
            $statement->bindValue(1, $studentId, SQLITE3_INTEGER);
            $statement->bindValue(2, $name, SQLITE3_TEXT);
            $statement->bindValue(3, $value, SQLITE3_INTEGER);
            $executeResult = $statement->execute();

            $success = $executeResult instanceof SQLite3Result;
        }

        return $success;
    }

    public function addAddFormError(string $error): void
    {
        $this->addFormErrors[] = $error;
    }

    public function addAddGradeFormError(string $error): void
    {
        $this->addGradeFormErrors[] = $error;
    }

    public function getAddFormErrors(): array
    {
        return $this->addFormErrors;
    }

    public function getAddGradeFormErrors(): array
    {
        return $this->addGradeFormErrors;
    }

    public function findAll(): array
    {
        $students = [];
        $db = new SQLite3('school_board_test');
        $resource = $db->query('SELECT * FROM student');
        while ($row = $resource->fetchArray()) {
            $students[] = $row;
        }

        return $students;
    }

    public function getDetails(int $id): array
    {
        $db = new SQLite3('school_board_test');
        $statement = $db->prepare(
            "select 
                    student.id as student_id,
                    student.name as student_name,
                    group_concat(grade.value) as grades_list,                    
                    avg(`value`) grades_avg                   
                from student                
                left join grade on grade.student_id = student.id
                where student.id = ?
                group by student.id
                ;"
        );

        $statement->bindValue(1, $id, SQLITE3_INTEGER);
        $executeResult = $statement->execute();

        $row = $executeResult->fetchArray();
        $details = [
            'id' => $row['student_id'],
            'name' => $row['student_name'],
            'grades_list' => $row['grades_list'],
            'grades_avg' => $row['grades_avg'],
        ];

        return $details;
    }
}
