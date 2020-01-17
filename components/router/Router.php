<?php

include_once('components\dashboard\Dashboard.php');
include_once('components\student\Student.php');
include_once('components\csmBoard\CsmBoard.php');
include_once('components\csmbBoard\CsmbBoard.php');

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
                        if ($student->add($studentName, $studentBoard)) {
                            header("Location: /?student=list");
                            return;
                        }
                    } catch (\TypeError $e) {
                        $student->addAddFormError('Can\'t add student!');
                    }
                }

                $student->displayAddForm();
                return;
            } elseif ('list' === $_GET['student']) {
                $student->displayList();
                return;
            } elseif(is_numeric($_GET['student'])) {
                if (array_key_exists('grade', $_GET)) {
                    if ('add' === $_GET['grade']) {
                        if (!empty($_POST)) {
                            $gradeName = $_POST['name'] ?? null;
                            $gradeValue = $_POST['value'] ?? null;

                            try{
                                if ($student->addGrade($_GET['student'], $gradeName, $gradeValue)) {
                                    header("Location: http://schoolboard.test?student=" . $_GET['student']);
                                    exit;
                                }

                            } catch (\TypeError $e) {
                                $student->addAddGradeFormError('Can\'t add grade!');
                            }
                        }

                        $student->displayAddGradeForm($_GET['student']);
                        return;
                    }
                } else {
                    $student->displayDetails($_GET['student']);
                    return;
                }
            }
        } elseif (array_key_exists('board', $_GET)) {
            if ('csm' === $_GET['board']) {
                $csmBoard = new CsmBoard();
                $csmBoard->display();
                return;
            } elseif ('csmb' === $_GET['board']) {
                $csmbBoard = new CsmbBoard();
                $csmbBoard->display();
                return;
            }
        }

        $dashboard = new Dashboard();
        $dashboard->display();
    }
}