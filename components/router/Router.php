<?php

include_once('components\dashboard\Dashboard.php');
include_once('components\student\Student.php');

class Router
{
    public function route()
    {
        if (array_key_exists('student', $_GET)) {
            $student = new Student();

            if ('add' === $_GET['student']) {

                if (!empty($_POST)) {
                    $studentName = $_POST['name'] ?? null;
                    $studentBoard = $_POST['board'] ?? null;

                    try{
                        $student->add($studentName, $studentBoard);
                    } catch (\TypeError $e) {
                        print_r($e);
                        $student->addAddFormError('Can\'t add student!');
                    }
                }

                $student->displayAddForm();
            }
        } else {
            $dashboard = new Dashboard();
            $dashboard->display();
        }
    }
}