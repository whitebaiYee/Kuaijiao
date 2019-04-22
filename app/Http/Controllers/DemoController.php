<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller {
	
    //测试
    public function haha() {
    	$data = 'hello';
    	return view('demo')->with('user', $data);
    }

    public function login() {
    	$stat = 111;
    	return view('login')->with('stat', $stat);
    }

    //登录
    public function user() {
    	session_start();
    	$id = $_SESSION['id'];
    	$username = $_SESSION['username'];
    	$user_type = $_SESSION['user_type'];
        $user = array('id' => $id, 'username' => $username, 'user_type' => $user_type);
    	if($user_type == 'o') {
            return view('operation')->with('user', $user);
        }
        else if($user_type == 't') {
            return view('teacher')->with('user', $user);
        }
        else {
            return view('student')->with('user', $user);
        }
    }

    //运维端学生查询
    public function selectStudent() {
        $conn = mysqli_connect("localhost", "root", "ydx970516", "kj");
        mysqli_select_db($conn, "kj") or die("数据库访问错误" . mysql_error());
        mysqli_query($conn, "set names UTF8");
        $result = mysqli_query($conn, "select * from students");
        $row_num = mysqli_num_rows($result);
        //var_dump($row_num);
        for($i = 0; $i < $row_num; $i++) { 
            $row[$i] = mysqli_fetch_assoc($result);
            //$row[$i] = mysqli_fetch_row($result, MYSQLI_ASSOC);
        }
        //var_dump($row);
        $student = array('row_num' => $row_num, 'row' => $row);
        //var_dump($student);
        mysqli_close($conn);
        return view('/operation/selectStudent')->with('student', $student);
    }

    //运维端教师查询
    public function selectTeacher() {
        $conn = mysqli_connect("localhost", "root", "ydx970516", "kj");
        mysqli_select_db($conn, "kj") or die("数据库访问错误" . mysql_error());
        mysqli_query($conn, "set names UTF8");
        $result = mysqli_query($conn, "select * from teachers");
        $row_num = mysqli_num_rows($result);
        //var_dump($row_num);
        for($i = 0; $i < $row_num; $i++) { 
            $row[$i] = mysqli_fetch_assoc($result);
            //$row[$i] = mysqli_fetch_row($result, MYSQLI_ASSOC);
        }
        //var_dump($row);
        $teacher = array('row_num' => $row_num, 'row' => $row);
        //var_dump($student);
        mysqli_close($conn);
        return view('/operation/selectTeacher')->with('teacher', $teacher);
    }

    //运维端课程查询
    public function selectCourse() {
        $conn = mysqli_connect("localhost", "root", "ydx970516", "kj");
        mysqli_select_db($conn, "kj") or die("数据库访问错误" . mysql_error());
        mysqli_query($conn, "set names UTF8");
        $result = mysqli_query($conn, "select * from courses");
        $row_num = mysqli_num_rows($result);
        //var_dump($row_num);
        for($i = 0; $i < $row_num; $i++) { 
            $row[$i] = mysqli_fetch_assoc($result);
            //$row[$i] = mysqli_fetch_row($result, MYSQLI_ASSOC);
        }
        //var_dump($row);
        $course = array('row_num' => $row_num, 'row' => $row);
        //var_dump($course);
        mysqli_close($conn);
        return view('/operation/selectCourse')->with('course', $course);
    }

    //运维端学生新增
    function insertStudent() {
        //在public/sinesrt.php中
        if(!isset($_POST['insertStudent'])) {
            exit('非法访问!');
        }
        $id = NULL;
        $username = NULL;
        $password = NULL;
        $email = 'NULL';
        $major = NULL;

        $id = $_POST['id'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $major = $_POST['major'];
        //包含数据库连接文件
        $conn = mysqli_connect("localhost", "root", "ydx970516", "kj");
        mysqli_select_db($conn, "kj") or die("数据库访问错误" . mysql_error());
        mysqli_query($conn, "set names UTF8");
        $sql = "insert into students values (" . $id . ", '" . $username . "', '" . $major . "', " . $email . ", '" . $password . "', NULL)";
        //var_dump($sql);
        $result = mysqli_query($conn, $sql);
        //var_dump($result);
        //var_dump($check_query);
        //var_dump(mysqli_fetch_array($check_query));
        if($result) {
            //var_dump($result);
            //登录成功
            echo "<script>alert('新增成功！');</script>";
            return view('/operation/insertStudent');
        }
        else {
            //var_dump($result);
            echo "<script>alert('新增失败！');</script>";
            return view('/operation/insertStudent');
        }
    }
}
