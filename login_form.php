<?php
session_start();
/*
 * Copyright 2013 by Allen Tucker. 
 * This program is part of RMHC-Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
 */
?><?php
/*
 * Created on Mar 28, 2008
 * @author Oliver Radwan <oradwan@bowdoin.edu>, Sam Roberts, Allen Tucker
 * @version 3/28/2008, revised 7/1/2015
 */
?>

<div id="content">
    <?PHP
    include_once('database/persondb.php');
    include_once('domain/Person.php');
    if (($_SERVER['PHP_SELF']) == "/logout.php") {
        //prevents infinite loop of logging in to the page which logs you out...
        echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
    }
    if (!array_key_exists('_submit_check', $_POST)) {
        echo('<div><p>Access to Homebase requires a Username and a Password. ' .
        '<ul>'
        );
        echo('<li>If you are applying for a volunteer position, enter the Username \'guest\' and a blank Password. ');
        echo('<li>If you are a volunteer logging in for the first time, your Username is your first name followed by your ten digit phone number. ' .
        'After you have logged in, you can change your password.  ');
        echo('<li>(If you are having difficulty logging in or have forgotten your Password, please contact either the 
        		<a href="mailto:allen@npfi.org"><i>Portland House Manager</i></a>
        		or the <a href="mailto:allen@npfi.org"><i>Bangor House Manager</i></a>.) ');
        echo '</ul>';
        echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true"><tr><td>Username:</td>
        		<td><input type="text" name="user" tabindex="1"></td></tr>
        		<tr><td>Password:</td><td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2"><input type="submit" name="Login" value="Login"></td></tr></table>');
    } else {
        //check if they logged in as a guest:
        if ($_POST['user'] == "guest" && $_POST['pass'] == "") {
            $_SESSION['logged_in'] = 1;
            $_SESSION['access_level'] = 0;
            $_SESSION['_id'] = "guest";
            echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
        }
        //otherwise authenticate their password
        else {
            $db_pass = md5($_POST['pass']);
            $db_user = $_POST['user'];
            $person = retrieve_person($db_user);
            if ($person) { //avoids null results
                if ($person->get_pass() == $db_pass) { //if the passwords match, login
                    $_SESSION['logged_in'] = 1;
                    date_default_timezone_set ("America/New_York");
                    if ($person->get_userType() == "applicant")
                        $_SESSION['access_level'] = 0;
                    else if ($person->get_userType() == "manager")
                        $_SESSION['access_level'] = 2;
                    else
                        $_SESSION['access_level'] = 1;
                    $_SESSION['f_name'] = $person->get_firstName();
                    $_SESSION['l_name'] = $person->get_lastName();
                    $_SESSION['_id'] = $_POST['user'];
                    echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
                }
                else {
                    echo('<div><p class="error">Error: invalid username/password<br />if you cannot remember your password, ask either the 
        		<a href="mailto:allen@npfi.org"><i>Portland House Manager</i></a>
        		or the <a href="mailto:allen@npfi.org"><i>Bangor House Manager</i></a>. to reset it for you.</p><p>Access to Homebase requires a Username and a Password. <p>For guest access, enter Username <strong>guest</strong> and no Password.</p>');
                    echo('<p>If you are a volunteer, your Username is your first name followed by your phone number with no spaces. ' .
                    'For instance, if your first name were John and your phone number were (207)-123-4567, ' .
                    'then your Username would be <strong>John2071234567</strong>.  ');
                    echo('If you do not remember your password, please contact either the 
        		<a href="mailto:allen@npfi.org"><i>Portland House Manager</i></a>
        		or the <a href="mailto:allen@npfi.org"><i>Bangor House Manager</i></a>.');
                    echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true"><tr><td>Username:</td><td><input type="text" name="user" tabindex="1"></td></tr><tr><td>Password:</td><td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2"><input type="submit" name="Login" value="Login"></td></tr></table>');
                }
            } else {
                //At this point, they failed to authenticate
                echo('<div><p class="error">Error: invalid username/password<br />if you cannot remember your password, ask the House Manager to reset it for you.</p><p>Access to Homebase requires a Username and a Password. <p>For guest access, enter Username <strong>guest</strong> and no Password.</p>');
                echo('<p>If you are a volunteer, your Username is your first name followed by your phone number with no spaces. ' .
                'For instance, if your first name were John and your phone number were (207)-123-4567, ' .
                'then your Username would be <strong>John2071234567</strong>.  ');
                echo('If you do not remember your password, please contact either the 
        		<a href="mailto:allen@npfi.org"><i>Portland House Manager</i></a>
        		or the <a href="mailto:allen@npfi.org"><i>Bangor House Manager</i></a>.');
                echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true"><tr><td>Username:</td><td><input type="text" name="user" tabindex="1"></td></tr><tr><td>Password:</td><td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2"><input type="submit" name="Login" value="Login"></td></tr></table>');
            }
        }
    }
    ?>
    <?PHP include('footer.inc'); ?>
</div>
