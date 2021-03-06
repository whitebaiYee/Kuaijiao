<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Storage;

class StudentController extends Controller {
	
    //学生端已选课程
    public function selectCourse() {
        session_start();
        $id = $_SESSION['id'];
        $conn = mysqli_connect("localhost", "root", "ydx970516", "kj");
        mysqli_select_db($conn, "kj") or die("数据库访问错误" . mysql_error());
        mysqli_query($conn, "set names UTF8");
        $result = mysqli_query($conn, "select * from student_course join students on students.id = student_course.studentid join courses on courses.courseid = student_course.courseid where studentid like '" . $id . "'");
        $row_num = mysqli_num_rows($result);
        //var_dump($row_num);
        $row[0] = NULL;
        for($i = 0; $i < $row_num; $i++) { 
            $row[$i] = mysqli_fetch_assoc($result);
            //$row[$i] = mysqli_fetch_row($result, MYSQLI_ASSOC);
        }
        //var_dump($row);
        $course = array('row_num' => $row_num, 'row' => $row);
        //var_dump($course);
        mysqli_close($conn);
        return view('/student/selectCourse')->with('course', $course);
    }

    //学生端查看作业
    public function selectHomework() {
        if(isset($_SESSION)) {
            $id = $_SESSION['id'];
        }
        else {
            session_start();
            $id = $_SESSION['id'];
        }
        $conn = mysqli_connect("localhost", "root", "ydx970516", "kj");
        mysqli_select_db($conn, "kj") or die("数据库访问错误" . mysql_error());
        mysqli_query($conn, "set names UTF8");
        $sql = "select * from course_homework join courses on course_homework.courseid = courses.courseid join student_course on student_course.courseid = courses.courseid join homeworks on homeworks.homeworkid = course_homework.homeworkid where studentid like '" . $id . "'";
        $result = mysqli_query($conn, $sql);
        $row_num = mysqli_num_rows($result);
        $row[0] = NULL;
        for($i = 0; $i < $row_num; $i++) { 
            $row[$i] = mysqli_fetch_assoc($result);
        }
        $course = array('row_num' => $row_num, 'row' => $row);
        mysqli_close($conn);
        return view('/student/selectHomework')->with('course', $course);
    }

    //学生端上传作业
    public function uphomework(Request $request) {
        if(isset($_SESSION)) {
            $id = $_SESSION['id'];
        }
        else {
            session_start();
            $id = $_SESSION['id'];
        }
        if($_FILES['URL']['error'] != 0) {
            echo "<script>alert('提交失败！');</script>";
            return StudentController::selectHomework();
        }
        else {
            $conn = mysqli_connect("localhost", "root", "ydx970516", "kj");
            mysqli_select_db($conn, "kj") or die("数据库访问错误" . mysql_error());
            mysqli_query($conn, "set names UTF8");
            $url = $request->URL->store('');
            $result = mysqli_query($conn, "select * from student_homework where homeworkid like '" . $_POST['homeworkid'] . "' and studentid like '" . $id . "'");
            $row_num = mysqli_num_rows($result);
            if($row_num == 1) {
                $deleteSql = "delete from student_homework where homeworkid like '" . $_POST['homeworkid'] . "' and studentid like '" . $id . "'";
                mysqli_query($conn, $deleteSql);
            }
            $sql = "insert into student_homework values ('" . $_POST['homeworkid'] . "', '" . $id . "', '" . $url . "', NULL)";
            $result = mysqli_query($conn, $sql);
            if($result) {
                echo "<script>alert('提交成功！');</script>";
                return StudentController::selectHomework();
            }
            else {
                echo "<script>alert('提交失败！');</script>";
                return StudentController::selectHomework();
            }
        }
    }

    //学生端下载作业附件
    public function downHomework() {
        if(isset($_SESSION)) {
            $id = $_SESSION['id'];
        }
        else {
            session_start();
            $id = $_SESSION['id'];
        }
        $conn = mysqli_connect("localhost", "root", "ydx970516", "kj");
        mysqli_select_db($conn, "kj") or die("数据库访问错误" . mysql_error());
        mysqli_query($conn, "set names UTF8");
        $result = mysqli_query($conn, "select * from homeworks where homeworkid like '" . $_GET['homeworkid'] . "'");
        $file = mysqli_fetch_assoc($result);
        $url = $file['URL'];
        $path = realpath(base_path('storage\app')) . "\\" . $url;
        return response()->download($path);
    }
}

