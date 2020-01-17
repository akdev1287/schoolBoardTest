<?php

class Student
{
    private $addFormErrors = [];

    public function displayAddForm()
    {
        $student = $this;

        ob_start();
        require 'addForm.html';
        ob_end_flush();
    }

    public function displayList()
    {
        $student = $this;

        ob_start();
        require 'list.html';
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

    public function addAddFormError(string $error): void
    {
        $this->addFormErrors[] = $error;
    }

    public function getAddFormErrors(): array
    {
        return $this->addFormErrors;
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
}
